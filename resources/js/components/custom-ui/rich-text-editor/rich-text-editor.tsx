import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { ScrollArea, ScrollBar } from '@/components/ui/scroll-area';
import { Separator } from '@/components/ui/separator';
import { cn } from '@/lib/utils';
import { Color } from '@tiptap/extension-color';
import Highlight from '@tiptap/extension-highlight';
import TextAlign from '@tiptap/extension-text-align';
import {
  AlignCenterIcon,
  AlignJustifyIcon,
  AlignLeftIcon,
  AlignRightIcon,
  BoldIcon,
  Heading1Icon,
  Heading2Icon,
  Heading3Icon,
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

import { EditorContent, EditorContext, useCurrentEditor, useEditor, useEditorState } from '@tiptap/react';
import { FloatingMenu } from '@tiptap/react/menus';

import { Input } from '@/components/ui/input';
import StarterKit from '@tiptap/starter-kit';
import { useMemo } from 'react';

export function RichTextEditor({ value, onChange, id }: { value?: string; onChange?: (html: string) => void; id?: string }) {
  const editor = useEditor({
    immediatelyRender: false,
    extensions: [
      StarterKit.configure({ link: { openOnClick: false } }),
      Highlight,
      Color,
      TextAlign.configure({ types: ['heading', 'paragraph'], alignments: ['left', 'center', 'right', 'justify'] }),
    ], // define your extension array
    content: value, // initial content
    onUpdate: ({ editor }) => {
      onChange?.(editor.getHTML());
    },
    editorProps: {
      attributes: {
        id: id ?? 'editor',
        class: 'prose prose-sm @sm:prose-base p-2 focus:outline-none dark:prose-invert @lg:prose-lg @xl:prose-2xl mx-auto',
      },
    },
  });
  const providerValue = useMemo(() => ({ editor }), [editor]);
  if (!editor) {
    return null;
  }
  return (
    <div className="@container rounded border shadow-xs">
      <EditorContext.Provider value={providerValue}>
        <MenuBar />
        <EditorContent editor={editor} />
        <FloatingMenu editor={editor}>
          <SmallMenuBar />
        </FloatingMenu>
      </EditorContext.Provider>
    </div>
  );
}

function SmallMenuBar() {
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
  const editorState = useEditorState({
    editor,
    selector: ({ editor }) => {
      if (!editor) {
        return null;
      }
      return {
        isLeftAligned: editor.isActive({ textAlign: 'left' }),
        isCenterAligned: editor.isActive({ textAlign: 'center' }),
        isRightAligned: editor.isActive({ textAlign: 'right' }),
        isJustifyAligned: editor.isActive({ textAlign: 'justify' }),
      };
    },
  });

  if (!editor || !editorState) {
    return null;
  }

  return (
    <>
      <MenuBarButton
        onClick={() => editor.chain().focus().setTextAlign('left').run()}
        disabled={!editor.can().chain().focus().setTextAlign('left').run()}
        active={editorState.isLeftAligned}
        icon={AlignLeftIcon}
      />

      <MenuBarButton
        onClick={() => editor.chain().focus().setTextAlign('center').run()}
        disabled={!editor.can().chain().focus().setTextAlign('center').run()}
        active={editorState.isCenterAligned}
        icon={AlignCenterIcon}
      />

      <MenuBarButton
        onClick={() => editor.chain().focus().setTextAlign('right').run()}
        disabled={!editor.can().chain().focus().setTextAlign('right').run()}
        active={editorState.isRightAligned}
        icon={AlignRightIcon}
      />

      <MenuBarButton
        aria-label="Align justify"
        onClick={() => editor.chain().focus().setTextAlign('justify').run()}
        disabled={!editor.can().chain().focus().setTextAlign('justify').run()}
        active={editorState.isJustifyAligned}
        icon={AlignJustifyIcon}
      />
    </>
  );
}
function HeadingsToolbar() {
  const { editor } = useCurrentEditor();
  const editorState = useEditorState({
    editor,
    selector: ({ editor }) => {
      if (!editor) {
        return null;
      }
      return {
        isHeading1: editor.isActive('heading', { level: 1 }),
        isHeading2: editor.isActive('heading', { level: 2 }),
        isHeading3: editor.isActive('heading', { level: 3 }),
      };
    },
  });
  if (!editor || !editorState) {
    return null;
  }

  return (
    <>
      <MenuBarButton
        onClick={() => editor.chain().focus().toggleHeading({ level: 1 }).run()}
        disabled={!editor.can().chain().focus().toggleHeading({ level: 1 }).run()}
        active={editorState.isHeading1}
        icon={Heading1Icon}
      />
      <MenuBarButton
        onClick={() => editor.chain().focus().toggleHeading({ level: 2 }).run()}
        disabled={!editor.can().chain().focus().toggleHeading({ level: 2 }).run()}
        active={editorState.isHeading2}
        icon={Heading2Icon}
      />
      <MenuBarButton
        onClick={() => editor.chain().focus().toggleHeading({ level: 3 }).run()}
        disabled={!editor.can().chain().focus().toggleHeading({ level: 3 }).run()}
        active={editorState.isHeading3}
        icon={Heading3Icon}
      />
    </>
  );
}
function FontsToolbar() {
  const { editor } = useCurrentEditor();

  const editorState = useEditorState({
    editor,
    selector: ({ editor }) => {
      if (!editor) {
        return null;
      }
      return {
        isBold: editor.isActive('bold'),
        isItalic: editor.isActive('italic'),
        isUnderline: editor.isActive('underline'),
        isStrikethrough: editor.isActive('strike'),
      };
    },
  });

  if (!editor || !editorState) {
    return null;
  }

  return (
    <>
      <MenuBarButton
        onClick={() => editor.chain().focus().toggleBold().run()}
        disabled={!editor.can().chain().focus().toggleBold().run()}
        icon={BoldIcon}
        active={editorState.isBold}
      />
      <MenuBarButton
        onClick={() => editor.chain().focus().toggleStrike().run()}
        disabled={!editor.can().chain().focus().toggleStrike().run()}
        active={editorState.isStrikethrough}
        icon={StrikethroughIcon}
      />
      <MenuBarButton
        onClick={() => editor.chain().focus().toggleItalic().run()}
        disabled={!editor.can().chain().focus().toggleItalic().run()}
        active={editorState.isItalic}
        icon={ItalicIcon}
      />
      <MenuBarButton
        onClick={() => editor.chain().focus().toggleUnderline().run()}
        disabled={!editor.can().chain().focus().toggleUnderline().run()}
        active={editorState.isUnderline}
        icon={UnderlineIcon}
      />
    </>
  );
}

function MenuBar() {
  const { editor } = useCurrentEditor();
  const editorState = useEditorState({
    editor,
    selector: ({ editor }) => {
      if (!editor) {
        return null;
      }
      return {
        isHighlightActive: editor.isActive('highlight'),
        isBulletListActive: editor.isActive('bulletList'),
        isOrderedListActive: editor.isActive('orderedList'),
        canSetLink: !editor.isActive('link'),
        canUnsetLink: editor.isActive('link'),
      };
    },
  });

  if (!editor || !editorState) {
    return null;
  }

  return (
    <ScrollArea className="bg-background/80 border-b">
      <div className="flex w-full flex-wrap items-center lg:flex-nowrap">
        <MenuBarButton onClick={() => editor.chain().focus().undo().run()} disabled={!editor.can().chain().focus().undo().run()} icon={UndoIcon} />
        <MenuBarButton onClick={() => editor.chain().focus().redo().run()} disabled={!editor.can().chain().focus().redo().run()} icon={RedoIcon} />

        <MenuBarSeparator />

        <FontsToolbar />
        <MenuBarButton
          onClick={() => editor.chain().focus().toggleHighlight().run()}
          disabled={!editor.can().chain().focus().toggleHighlight().run()}
          active={editorState.isHighlightActive}
          icon={HighlighterIcon}
        />
        <Button className="size-8 cursor-pointer rounded-none border-0 p-0.5 shadow-none" asChild variant="ghost" size="icon">
          <Input
            type="color"
            value={editor.getAttributes('textStyle').color || '#000000'}
            onChange={(e) => editor.chain().focus().setColor(e.target.value).run()}
          />
        </Button>
        <MenuBarSeparator />
        <HeadingsToolbar />

        <MenuBarSeparator />

        <AlignmentToolbar />

        <MenuBarSeparator />
        <MenuBarButton
          onClick={() => editor.chain().focus().toggleBulletList().run()}
          disabled={!editor.can().chain().focus().toggleBulletList().run()}
          active={editorState.isBulletListActive}
          icon={ListIcon}
        />

        <MenuBarButton
          onClick={() => editor.chain().focus().toggleOrderedList().run()}
          disabled={!editor.can().chain().focus().toggleOrderedList().run()}
          active={editorState.isOrderedListActive}
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
          disabled={!editorState.canSetLink}
          data-state={editor.isActive('link') ? 'on' : 'off'}
          icon={LinkIcon}
        />

        <MenuBarButton
          onClick={() => {
            editor.chain().focus().unsetLink().run();
          }}
          disabled={!editorState.canUnsetLink}
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
      className={cn('cursor-pointer rounded-none first:rounded-tl last:rounded-tr', {
        'bg-accent': active,
      })}
      variant="ghost"
      size="icon-xs"
      onClick={onClick}
      disabled={disabled}
    >
      {<Icon className="size-4" />}
    </Button>
  );
}
