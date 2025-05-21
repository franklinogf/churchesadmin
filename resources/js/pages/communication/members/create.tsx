import AppLayout from '@/layouts/app-layout';

export default function Create() {
  return (
    <AppLayout title="Create Member" breadcrumbs={[{ title: 'Members', href: route('members.index') }, { title: 'Create' }]}>
      <h1>hola</h1>
    </AppLayout>
  );
}
