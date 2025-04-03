import { Head } from '@inertiajs/react';

export default function Welcome() {
    return (
        <div className="container flex h-screen flex-col items-center justify-center">
            <Head title="Welcome" />
            <a href="http://tenant1.churchroll.test" className="btn btn-primary">
                Go to tenant
            </a>
        </div>
    );
}
