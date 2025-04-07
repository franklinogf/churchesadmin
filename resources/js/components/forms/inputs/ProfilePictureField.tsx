import { useTranslations } from "@/hooks/translations";
import { cn } from "@/lib/utils";
import { FileField } from "./FileField";

interface ProfilePictureFieldProps {
  label?: string;
  error?: string;
  initialFile?: string;
  disabled?: boolean;
  onChange?: (value: string) => void;
  className?: string;
}

export function ProfilePictureField({
  label,
  initialFile,
  error,
  disabled,
  onChange,
  className,
}: ProfilePictureFieldProps) {
  const { t } = useTranslations();
  label = label ?? t("Foto de Perfil");
  return (
    <FileField
      label={label}
      error={error}
      disabled={disabled}
      className={cn(className, "size-50")}
      labelClassName="block text-center"
      labelIdle={t(
        "Arrastra y suelta tu foto aquí o haz <span class='filepond--label-action'>clic</span> para seleccionarla",
      )}
      allowMultiple={false}
      initialFiles={initialFile ? [initialFile] : []}
      onChange={(files) => {
        onChange?.(files[0] ?? "");
      }}
      //   imagePreviewHeight={100}
      //   imageCropAspectRatio="1:1"
      //   imageResizeTargetWidth={200}
      //   imageResizeTargetHeight={200}
      //   imageResizeMode="compact circle"
      stylePanelLayout={"compact circle"}
      styleLoadIndicatorPosition="center"
      styleProgressIndicatorPosition="center bottom"
      styleButtonRemoveItemPosition="center bottom"
      styleButtonProcessItemPosition="center bottom"
      allowProcess={false}
      allowReplace={true}
    />
  );
}
