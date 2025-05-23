import { PageTitle } from '@/components/PageTitle';
import AppLayout from '@/layouts/app-layout';
import type { Member } from '@/types/models/member';
import type { Missionary } from '@/types/models/missionary';
import { Link } from '@inertiajs/react';

interface EmailsPageProps {
  members: Member[];
  missionaries: Missionary[];
}
export default function EmailsPage({ members, missionaries }: EmailsPageProps) {
  return (
    <AppLayout title="Emails">
      <PageTitle description="Manage your emails here.">Emails</PageTitle>
      <Link href={route('communication.emails.members')}>Manage Email Recipients</Link>
    </AppLayout>
  );
}
