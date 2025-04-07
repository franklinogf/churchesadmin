import { useTranslations } from "@/hooks/translations";
import { PageProps } from "@/types";
import { usePage } from "@inertiajs/react";
import { FilePondFile } from "filepond";
import FilePondPluginImageExifOrientation from "filepond-plugin-image-exif-orientation";
import FilePondPluginImagePreview from "filepond-plugin-image-preview";
import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css";
import "filepond/dist/filepond.min.css";
import { useEffect, useRef, useState } from "react";
import { FilePond, FilePondProps, registerPlugin } from "react-filepond";
import { FieldContainer } from "./FieldContainer";
import { FieldError } from "./FieldError";
import { FieldLabel } from "./FieldLabel";
registerPlugin(FilePondPluginImagePreview, FilePondPluginImageExifOrientation);
export interface FileFieldProps
  extends Omit<
    FilePondProps,
    | "server"
    | "onupdatefiles"
    | "onprocessfiles"
    | "onremovefile"
    | "files"
    | "credits"
    | "className"
  > {
  onChange?: (value: string[]) => void;
  initialFiles?: string[];
  label?: string;
  error?: string;
  labelClassName?: string;
  className?: string;
}
export function FileField({
  label,
  error,
  disabled,
  initialFiles,
  labelIdle,
  onChange,
  allowImagePreview = true,
  className,
  labelClassName,
  ...props
}: FileFieldProps) {
  const { t } = useTranslations();
  const { csrf_token } = usePage<PageProps>().props;
  const [files, setFiles] = useState<FilePondFile[]>([]);

  labelIdle =
    labelIdle ??
    t("Arrastra y suelta tus archivos o <span class='filepond--label-action'>Buscar</span>");

  const filePondRef = useRef<FilePond | null>(null);

  useEffect(() => {
    if (initialFiles && initialFiles.length > 0) {
      initialFiles.forEach((file) => {
        filePondRef.current?.addFile(file, { type: "local" });
      });
    }
  }, []);

  function handleFileChange() {
    onChange?.(filePondRef.current?.getFiles().map((file) => file.serverId) ?? []);
  }

  return (
    <FieldContainer className={className}>
      <FieldLabel className={labelClassName} label={label} error={error} />
      <FilePond
        disabled={disabled}
        allowImagePreview={allowImagePreview}
        ref={filePondRef}
        className="w-full"
        labelIdle={labelIdle}
        files={files as unknown as string[]}
        credits={false}
        server={{
          load: (source, load) => {
            fetch(source)
              .then((res) => res.blob())
              .then(load);
          },
          url: "/upload",
          headers: {
            "X-CSRF-TOKEN": csrf_token,
          },
        }}
        onupdatefiles={(fileItems) => {
          setFiles(fileItems.map((fileItem) => fileItem));
        }}
        onprocessfiles={handleFileChange}
        onremovefile={handleFileChange}
        {...props}
      />
      <FieldError error={error} />
    </FieldContainer>
  );
}
