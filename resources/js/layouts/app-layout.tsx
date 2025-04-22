import ConfirmationDialog from '@/components/ConfirmationDialog';
import AppLayoutTemplate from '@/layouts/app/app-sidebar-layout';
import { type SharedData, type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/react';
import { useEffect, type ReactNode } from 'react';
import { toast, Toaster } from 'sonner';

interface AppLayoutProps {
  children: ReactNode;
  breadcrumbs?: BreadcrumbItem[];
  title: string;
}

export default ({ children, breadcrumbs, title, ...props }: AppLayoutProps) => {
  const { props: pageProps } = usePage<SharedData>();
  useEffect(() => {
    if (pageProps.flash.success) {
      toast.success(pageProps.flash.success);
    }
    if (pageProps.flash.error) {
      toast.error(pageProps.flash.error);
    }
  }, [pageProps.flash]);
  return (
    <AppLayoutTemplate breadcrumbs={breadcrumbs} {...props}>
      <Head title={title} />
      <div className="flex h-full flex-1 flex-col p-4">{children}</div>
      <ConfirmationDialog />
      <Toaster richColors />
    </AppLayoutTemplate>
  );
};
