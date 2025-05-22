import { useTranslations } from '@/hooks/use-translations';
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginImageExifOrientation from 'filepond-plugin-image-exif-orientation';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css';
import 'filepond/dist/filepond.min.css';
import { FilePond, registerPlugin } from 'react-filepond';

// Register the plugins
registerPlugin(FilePondPluginImageExifOrientation, FilePondPluginImagePreview, FilePondPluginFileValidateSize, FilePondPluginFileValidateType);
type FileType =
  | 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
  | 'application/vnd.openxmlformats-officedocument.presentationml.presentation'
  | 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
  | 'application/vnd.ms-excel'
  | 'application/pdf'
  | 'application/rtf'
  | 'image/png'
  | 'image/jpeg'
  | 'image/jpg'
  | 'image/gif'
  | 'image/webp'
  | 'image/svg+xml'
  | 'text/csv'
  | 'text/plain'
  | 'text/html'
  | 'video/mp4'
  | 'video/avi'
  | 'video/mpeg'
  | 'audio/mpeg'
  | 'audio/wav';

type FileTypeName = 'images' | 'documents' | 'videos' | 'audios';

const fileExpectedTypesMap: Record<FileType, string> = {
  'application/vnd.openxmlformats-officedocument.wordprocessingml.document': 'DOCX',
  'application/vnd.openxmlformats-officedocument.presentationml.presentation': 'PPTX',
  'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet': 'XLSX',
  'application/vnd.ms-excel': 'XLS',
  'application/pdf': 'PDF',
  'application/rtf': 'RTF',
  'image/png': 'PNG',
  'image/jpeg': 'JPEG',
  'image/jpg': 'JPG',
  'image/gif': 'GIF',
  'image/webp': 'WEBP',
  'image/svg+xml': 'SVG',
  'text/csv': 'CSV',
  'text/plain': 'Plain text',
  'text/html': 'HTML',
  'video/mp4': 'MP4',
  'video/avi': 'AVI',
  'video/mpeg': 'MPEG',
  'audio/mpeg': 'MPEG',
  'audio/wav': 'WAV',
};

const fileTypeMap: Record<FileTypeName, FileType[]> = {
  images: ['image/png', 'image/jpeg', 'image/jpg', 'image/gif', 'image/webp', 'image/svg+xml'],
  documents: [
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'application/vnd.openxmlformats-officedocument.presentationml.presentation',
    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    'application/vnd.ms-excel',
    'application/pdf',
    'application/rtf',
    'text/csv',
    'text/plain',
    'text/html',
  ],
  videos: ['video/mp4', 'video/avi', 'video/mpeg'],
  audios: ['audio/mpeg', 'audio/wav'],
};

export interface FileUploaderProps {
  onFileChange?: (files: File[]) => void;
  allowMultiple?: boolean;
  maxFiles?: number;
  name?: string;
  labelIdle?: string;
  acceptedFiles?: (FileType | FileTypeName)[];
  maxFileSize?: string | null;
  maxTotalFileSize?: string | null;
  disabled?: boolean;
  required?: boolean;
  className?: string;
  layout?: 'compact' | 'circle' | 'compact circle' | null;
  initialFiles?: string[];
}

export function FileUploader({
  onFileChange,
  allowMultiple,
  maxFiles = 3,
  name = 'media',
  labelIdle,
  acceptedFiles,
  maxFileSize = null,
  maxTotalFileSize = null,
  disabled,
  required,
  className,
  layout,
  initialFiles,
}: FileUploaderProps) {
  const { tChoice } = useTranslations();

  const acceptedFileTypes = acceptedFiles?.flatMap((fileType) => fileTypeMap[fileType as FileTypeName] ?? fileType) ?? undefined;

  return (
    <div className={className}>
      <FilePond
        stylePanelLayout={layout}
        files={initialFiles}
        onupdatefiles={(fileItems) => {
          onFileChange?.(fileItems.map((fileItem) => fileItem.file as File));
        }}
        required={required}
        disabled={disabled}
        maxFileSize={maxFileSize}
        maxTotalFileSize={maxTotalFileSize}
        acceptedFileTypes={acceptedFileTypes}
        allowMultiple={allowMultiple}
        maxFiles={maxFiles}
        name={name}
        labelIdle={labelIdle ?? `Drag & Drop your files or <span class="filepond--label-action">Browse</span>`}
        labelMaxFileSizeExceeded={'File is too large'}
        labelMaxFileSize={'Maximum file size is {filesize}'}
        labelMaxTotalFileSizeExceeded={'Maximum total size exceeded'}
        labelMaxTotalFileSize={'Maximum total file size is {filesize}'}
        labelFileTypeNotAllowed={'File type is not allowed'}
        fileValidateTypeLabelExpectedTypes={tChoice('Expected type: {lastType}|Expected types: {allButLastType} or {lastType}', 1)}
        fileValidateTypeLabelExpectedTypesMap={fileExpectedTypesMap}
        credits={false}
      />
    </div>
  );
}
