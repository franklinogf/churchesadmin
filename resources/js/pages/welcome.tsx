import { Button } from '@/components/ui/button';
import { NavigationMenu, NavigationMenuItem, NavigationMenuLink, NavigationMenuList } from '@/components/ui/navigation-menu';
import type { SharedData } from '@/types';
import { Head, Link, usePage } from '@inertiajs/react';

import { DollarSignIcon, GlobeIcon, TvIcon, UsersIcon } from 'lucide-react';
import { motion } from 'motion/react';

export const menuItems = [] as const;

export default function Welcome() {
  const { appName } = usePage<SharedData>().props;
  return (
    <>
      <Head title="Effortless Church Administration" />
      <main className="bg-white text-black">
        <header className="sticky top-0 z-40 flex h-16 w-full items-center justify-between bg-white px-4 py-2.5 shadow-xl">
          <p className="flex gap-x-1 font-bold">
            <TvIcon />
            {appName}
          </p>
          <nav className="flex justify-between">
            <NavigationMenu>
              <NavigationMenuList>
                {menuItems.map(({ label, url }) => (
                  <NavigationMenuItem key={label}>
                    <Link href={url}>
                      <NavigationMenuLink>{label}</NavigationMenuLink>
                    </Link>
                  </NavigationMenuItem>
                ))}
                <NavigationMenuItem asChild>
                  <CtaButton />
                </NavigationMenuItem>
              </NavigationMenuList>
            </NavigationMenu>
          </nav>
        </header>
        <motion.section
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.6 }}
          className="mx-auto max-w-3xl px-6 py-24 text-center"
        >
          <div className="my-2 flex flex-col items-center">
            <h1 className="my-2 w-2xl text-center text-5xl font-semibold">
              Effortless Church <span className="text-brand">Administration</span>
            </h1>
            <p className="max-w-2xl py-10 text-center text-lg">
              Churchroll helps you manage your church with ease, while also keeping track of offerings, members and missionaries - all in one
              beautiful dashboard.
            </p>

            <CtaButton />
          </div>
        </motion.section>

        {/* Features Section */}
        <section className="bg-brand/10 px-6 py-20">
          <div className="mx-auto grid max-w-5xl gap-10 text-center md:grid-cols-3">
            <motion.div initial={{ opacity: 0, y: 20 }} whileInView={{ opacity: 1, y: 0 }} viewport={{ once: true }} transition={{ delay: 0.1 }}>
              <DollarSignIcon className="text-brand mx-auto mb-4" size={32} />
              <h3 className="mb-2 text-xl font-semibold">Clergy Payroll Made Easy</h3>
              <p className="text-gray-600">Handle housing allowances, tax complexities, and direct deposits - all automated and accurate.</p>
            </motion.div>

            <motion.div initial={{ opacity: 0, y: 20 }} whileInView={{ opacity: 1, y: 0 }} viewport={{ once: true }} transition={{ delay: 0.2 }}>
              <UsersIcon className="text-brand mx-auto mb-4" size={32} />
              <h3 className="mb-2 text-xl font-semibold">Member Management</h3>
              <p className="text-gray-600">Keep detailed records, contact info, and family ties for every member of your congregation.</p>
            </motion.div>

            <motion.div initial={{ opacity: 0, y: 20 }} whileInView={{ opacity: 1, y: 0 }} viewport={{ once: true }} transition={{ delay: 0.3 }}>
              <GlobeIcon className="text-brand mx-auto mb-4" size={32} />
              <h3 className="mb-2 text-xl font-semibold">Missionary Oversight</h3>
              <p className="text-gray-600">Track assignments, support levels, communication logs, and prayer requests with ease.</p>
            </motion.div>
          </div>
        </section>

        {/* Testimonials */}
        <motion.section
          initial={{ opacity: 0 }}
          whileInView={{ opacity: 1 }}
          viewport={{ once: true }}
          transition={{ duration: 0.5 }}
          className="px-6 py-20 text-center"
        >
          <h2 className="mb-10 text-3xl font-bold">What Pastors Are Saying</h2>
          <div className="mx-auto max-w-3xl space-y-10">
            <blockquote className="text-gray-700 italic">
              “Churchroll gave us peace of mind. Payroll is no longer a burden, and our team finally has time to focus on ministry.”
              <br />
              <span className="mt-3 block font-semibold">- Pastor Maria, Lighthouse Church</span>
            </blockquote>
            <blockquote className="text-gray-700 italic">
              “I love how we can manage both members and missionaries from the same place. It's truly built for the church.”
              <br />
              <span className="mt-3 block font-semibold">- Rev. Daniel, Hope Mission Center</span>
            </blockquote>
          </div>
        </motion.section>

        {/* Call to Action */}
        <motion.section
          initial={{ opacity: 0, y: 20 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          transition={{ duration: 0.6 }}
          className="bg-brand/10 px-6 py-24 text-center"
        >
          <h2 className="mb-4 text-3xl font-bold">Ready to See Churchroll in Action?</h2>
          <p className="mb-6 text-lg">Let us walk you through how Churchroll can serve your ministry's needs.</p>
          <CtaButton />
        </motion.section>

        {/* Footer */}
        <footer className="px-6 py-10 text-center text-sm text-gray-500">
          <div>
            <p>Contact us: support@churchroll.com | (000) 000-0000</p>
            <p>&copy; {new Date().getFullYear()} Churchroll. All rights reserved.</p>
          </div>
        </footer>
      </main>
    </>
  );
}

function CtaButton() {
  return (
    <Button variant="brand">
      <Link href={route('root.home')} prefetch>
        Schedule a demo
      </Link>
    </Button>
  );
}
