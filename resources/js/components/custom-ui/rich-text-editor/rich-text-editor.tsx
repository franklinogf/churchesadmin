import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { ScrollArea, ScrollBar } from '@/components/ui/scroll-area';
import { Separator } from '@/components/ui/separator';
import { cn } from '@/lib/utils';
import Highlight from '@tiptap/extension-highlight';
import Link from '@tiptap/extension-link';
import TextAlign from '@tiptap/extension-text-align';
import Underline from '@tiptap/extension-underline';
import { EditorProvider, FloatingMenu, useCurrentEditor } from '@tiptap/react';
import StarterKit from '@tiptap/starter-kit';
import {
  AlignCenterIcon,
  AlignJustifyIcon,
  AlignLeftIcon,
  AlignRightIcon,
  BoldIcon,
  Heading1Icon,
  Heading2Icon,
  Heading3Icon,
  Heading4Icon,
  HighlighterIcon,
  ItalicIcon,
  LinkIcon,
  ListIcon,
  ListOrderedIcon,
  RedoIcon,
  StrikethroughIcon,
  UnderlineIcon,
  UndoIcon,
  UnlinkIcon,
  type LucideIcon,
} from 'lucide-react';

// define your extension array
const extensions = [
  StarterKit.configure({ heading: { levels: [1, 2, 3, 4] } }),
  TextAlign.configure({ types: ['heading', 'paragraph'], alignments: ['left', 'center', 'right', 'justify'] }),
  Underline,
  Highlight,
  Link.configure({ linkOnPaste: true, openOnClick: false }),
];

export function RichTextEditor({
  value,
  onChange,
  editorClassName,
  id,
}: {
  value?: string;
  onChange?: (html: string) => void;
  editorClassName?: string;

  id?: string;
}) {
  return (
    <div className="rounded border-1 shadow-xs">
      <EditorProvider
        extensions={extensions}
        content={value}
        slotBefore={<MenuBar />}
        onUpdate={({ editor }) => {
          onChange?.(editor.getHTML());
        }}
        // element={document.body}
        editorProps={{
          attributes: {
            id: id ?? 'editor',
            class: cn(
              'border-input field-sizing-content min-h-16 w-full min-w-full px-3 py-2 transition-[color,box-shadow] outline-none focus-visible:ring-0 disabled:cursor-not-allowed disabled:opacity-50',
              'prose prose-sm dark:prose-invert sm:prose-base prose-stone',
              editorClassName,
            ),
          },
        }}
      >
        <FloatingMenu tippyOptions={{ duration: 500 }} editor={null}>
          <SmallMenuBar />
        </FloatingMenu>
      </EditorProvider>
    </div>
  );
}

function SmallMenuBar() {
  const { editor } = useCurrentEditor();
  if (!editor) {
    return null;
  }
  return (
    <Card className="rounded-none p-0">
      <CardContent className="flex items-center gap-x-1 p-0">
        <FontsToolbar />

        <MenuBarSeparator />

        <HeadingsToolbar />

        <MenuBarSeparator />

        <AlignmentToolbar />
      </CardContent>
    </Card>
  );
}

function AlignmentToolbar() {
  const { editor } = useCurrentEditor();
  if (!editor) {
    return null;
  }
  return (
    <>
      <MenuBarButton
        onClick={() => editor.chain().focus().setTextAlign('left').run()}
        disabled={!editor.can().chain().focus().setTextAlign('left').run()}
        active={editor.isActive({ textAlign: 'left' })}
        icon={AlignLeftIcon}
      />

      <MenuBarButton
        onClick={() => editor.chain().focus().setTextAlign('center').run()}
        disabled={!editor.can().chain().focus().setTextAlign('center').run()}
        active={editor.isActive({ textAlign: 'center' })}
        icon={AlignCenterIcon}
      />

      <MenuBarButton
        onClick={() => editor.chain().focus().setTextAlign('right').run()}
        disabled={!editor.can().chain().focus().setTextAlign('right').run()}
        active={editor.isActive({ textAlign: 'right' })}
        icon={AlignRightIcon}
      />

      <MenuBarButton
        aria-label="Align justify"
        onClick={() => editor.chain().focus().setTextAlign('justify').run()}
        disabled={!editor.can().chain().focus().setTextAlign('justify').run()}
        active={editor.isActive({ textAlign: 'justify' })}
        icon={AlignJustifyIcon}
      />
    </>
  );
}
function HeadingsToolbar() {
  const { editor } = useCurrentEditor();
  if (!editor) {
    return null;
  }
  return (
    <>
      <MenuBarButton
        onClick={() => editor.chain().focus().toggleHeading({ level: 1 }).run()}
        disabled={!editor.can().chain().focus().toggleHeading({ level: 1 }).run()}
        active={editor.isActive('heading', { level: 1 })}
        icon={Heading1Icon}
      />
      <MenuBarButton
        onClick={() => editor.chain().focus().toggleHeading({ level: 2 }).run()}
        disabled={!editor.can().chain().focus().toggleHeading({ level: 2 }).run()}
        active={editor.isActive('heading', { level: 2 })}
        icon={Heading2Icon}
      />
      <MenuBarButton
        onClick={() => editor.chain().focus().toggleHeading({ level: 3 }).run()}
        disabled={!editor.can().chain().focus().toggleHeading({ level: 3 }).run()}
        active={editor.isActive('heading', { level: 3 })}
        icon={Heading3Icon}
      />
      <MenuBarButton
        onClick={() => editor.chain().focus().toggleHeading({ level: 4 }).run()}
        disabled={!editor.can().chain().focus().toggleHeading({ level: 4 }).run()}
        active={editor.isActive('heading', { level: 4 })}
        icon={Heading4Icon}
      />
    </>
  );
}
function FontsToolbar() {
  const { editor } = useCurrentEditor();
  if (!editor) {
    return null;
  }
  return (
    <>
      <MenuBarButton
        onClick={() => editor.chain().focus().toggleBold().run()}
        disabled={!editor.can().chain().focus().toggleBold().run()}
        icon={BoldIcon}
        active={editor.isActive('bold')}
      />
      <MenuBarButton
        onClick={() => editor.chain().focus().toggleStrike().run()}
        disabled={!editor.can().chain().focus().toggleStrike().run()}
        active={editor.isActive('strike')}
        icon={StrikethroughIcon}
      />
      <MenuBarButton
        onClick={() => editor.chain().focus().toggleItalic().run()}
        disabled={!editor.can().chain().focus().toggleItalic().run()}
        active={editor.isActive('italic')}
        icon={ItalicIcon}
      />
      <MenuBarButton
        onClick={() => editor.chain().focus().toggleUnderline().run()}
        disabled={!editor.can().chain().focus().toggleUnderline().run()}
        active={editor.isActive('underline')}
        icon={UnderlineIcon}
      />
    </>
  );
}

function MenuBar() {
  const { editor } = useCurrentEditor();
  if (!editor) {
    return null;
  }
  return (
    <ScrollArea className="border-b-1">
      <div className="flex w-full flex-wrap items-center lg:flex-nowrap">
        <MenuBarButton onClick={() => editor.chain().focus().undo().run()} disabled={!editor.can().chain().focus().undo().run()} icon={UndoIcon} />
        <MenuBarButton onClick={() => editor.chain().focus().redo().run()} disabled={!editor.can().chain().focus().redo().run()} icon={RedoIcon} />

        <MenuBarSeparator />

        <FontsToolbar />
        <MenuBarButton
          onClick={() => editor.chain().focus().toggleHighlight().run()}
          disabled={!editor.can().chain().focus().toggleHighlight().run()}
          active={editor.isActive('highlight')}
          icon={HighlighterIcon}
        />
        <MenuBarSeparator />
        <HeadingsToolbar />

        <MenuBarSeparator />

        <AlignmentToolbar />

        <MenuBarSeparator />
        <MenuBarButton
          onClick={() => editor.chain().focus().toggleBulletList().run()}
          disabled={!editor.can().chain().focus().toggleBulletList().run()}
          active={editor.isActive('bulletList')}
          icon={ListIcon}
        />

        <MenuBarButton
          onClick={() => editor.chain().focus().toggleOrderedList().run()}
          disabled={!editor.can().chain().focus().toggleOrderedList().run()}
          active={editor.isActive('orderedList')}
          icon={ListOrderedIcon}
        />
        <MenuBarSeparator />
        <MenuBarButton
          onClick={() => {
            const url = window.prompt('Enter URL');
            if (url) {
              editor
                .chain()
                .focus()
                .setLink({
                  href: url,
                  target: '_blank',
                  rel: 'noopener noreferrer',
                })
                .run();
            }
          }}
          disabled={!editor.can().chain().focus().setLink({ href: 'https://example.com' }).run()}
          data-state={editor.isActive('link') ? 'on' : 'off'}
          icon={LinkIcon}
        />

        <MenuBarButton
          onClick={() => {
            editor.chain().focus().unsetLink().run();
          }}
          disabled={!editor.can().chain().focus().unsetLink().run()}
          icon={UnlinkIcon}
        />
      </div>
      <ScrollBar orientation="horizontal" className="h-1.5!" />
    </ScrollArea>
  );
}

function MenuBarSeparator() {
  return <Separator orientation="vertical" className="mx-px h-6!" />;
}

function MenuBarButton({ onClick, disabled, icon: Icon, active }: { onClick: () => void; disabled: boolean; icon: LucideIcon; active?: boolean }) {
  return (
    <Button
      type="button"
      className={cn('size-8 cursor-pointer rounded-none', {
        'bg-accent': active,
      })}
      variant="ghost"
      size="icon"
      onClick={onClick}
      disabled={disabled}
    >
      {<Icon className="h-4 w-4" />}
    </Button>
  );
}
