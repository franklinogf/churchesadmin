import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Drawer, DrawerClose, DrawerContent, DrawerDescription, DrawerHeader, DrawerTitle } from '@/components/ui/drawer';
import { useIsMobile } from '@/hooks/use-mobile';
import { useTranslations } from '@/hooks/use-translations';
import { SubmitButton } from './forms/SubmitButton';
import { Button } from './ui/button';

interface ResponsiveModalProps {
  title: string;
  description?: string;
  open?: boolean;
  setOpen?: (open: boolean) => void;
  children: React.ReactNode;
}

export function ResponsiveModal({ title, description, open, setOpen, children }: ResponsiveModalProps) {
  const isMobile = useIsMobile();
  if (isMobile) {
    return (
      <Drawer open={open} onOpenChange={setOpen}>
        <DrawerContent>
          <DrawerHeader className="text-left">
            <DrawerTitle>{title}</DrawerTitle>
            <DrawerDescription>{description}</DrawerDescription>
          </DrawerHeader>
          <div className="max-w-xs:px-0 mx-auto w-full px-4 pb-4">{children}</div>
        </DrawerContent>
      </Drawer>
    );
  }
  return (
    <Dialog open={open} onOpenChange={setOpen}>
      <DialogContent>
        <DialogHeader>
          <DialogTitle>{title}</DialogTitle>
          <DialogDescription>{description}</DialogDescription>
        </DialogHeader>
        {children}
      </DialogContent>
    </Dialog>
  );
}

export function ResponsiveModalFooterSubmit({ isSubmitting, label }: { isSubmitting: boolean; label: string }) {
  const { t } = useTranslations();
  return (
    <div className="grid grid-cols-1 gap-2 md:flex md:justify-end md:gap-4">
      <DrawerClose asChild>
        <Button variant="outline" className="order-2 max-md:w-full md:order-1">
          {t('Cancel')}
        </Button>
      </DrawerClose>
      <SubmitButton className="order-1 max-md:w-full md:order-2" isSubmitting={isSubmitting}>
        {label}
      </SubmitButton>
    </div>
  );
}
