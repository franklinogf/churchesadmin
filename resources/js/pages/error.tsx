import { PageTitle } from '@/components/PageTitle';
import AppLayout from '@/layouts/app-layout';

export default function ErrorPage({ status, message }: { status: 503 | 500 | 404 | 403; message: string | null }) {
  const title = {
    503: '503: Service Unavailable',
    500: '500: Server Error',
    404: '404: Page Not Found',
    403: '403: Forbidden',
  }[status];

  const description = {
    503: 'Sorry, we are doing some maintenance. Please check back soon.',
    500: 'Whoops, something went wrong on our servers.',
    404: 'Sorry, the page you are looking for could not be found.',
    403: 'Sorry, you are forbidden from accessing this page.',
  }[status];

  return (
    <AppLayout title={title}>
      <div className="flex min-h-screen items-center px-4 py-12 sm:px-6 md:px-8 lg:px-12 xl:px-16">
        <div className="w-full space-y-6 text-center">
          <div className="space-y-3">
            <PageTitle className="text-4xl font-bold tracking-tighter sm:text-5xl">{title}</PageTitle>
            <p className="text-gray-500">{message || description}</p>
          </div>
        </div>
      </div>
    </AppLayout>
  );
}
