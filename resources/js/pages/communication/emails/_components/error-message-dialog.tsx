import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { ScrollArea, ScrollBar } from '@/components/ui/scroll-area';
import { useTranslations } from '@/hooks/use-translations';
import type { Email } from '@/types/models/email';

interface ErrorMessageDialogProps {
  email: Email;
  open: boolean;
  setOpen: (open: boolean) => void;
}
export function ErrorMessageDialog({ email, open, setOpen }: ErrorMessageDialogProps) {
  const { t } = useTranslations();
  return (
    <Dialog open={open} onOpenChange={setOpen}>
      <DialogContent className="sm:max-w-150">
        <DialogHeader>
          <DialogTitle>{t('Error message')}</DialogTitle>
          <DialogDescription>{t('Email error if any')}</DialogDescription>
        </DialogHeader>

        <ScrollArea className="max-h-100 overflow-hidden">
          <div className="prose dark:prose-invert prose-sky">
            <pre className="max-w-full text-balance">{email.errorMessage ?? t('No error message available')}</pre>
          </div>
          <ScrollBar orientation="horizontal" />
        </ScrollArea>

        <DialogFooter>
          <DialogClose asChild>
            <Button variant="outline">{t('Close')}</Button>
          </DialogClose>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  );
}
