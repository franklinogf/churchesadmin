import { Button } from '@/components/ui/button';
import { NavigationMenu, NavigationMenuItem, NavigationMenuLink, NavigationMenuList } from '@/components/ui/navigation-menu';
import type { SharedData } from '@/types';
import { Head, Link, usePage } from '@inertiajs/react';

import { useTranslations } from '@/hooks/use-translations';
import { DollarSignIcon, GlobeIcon, TvIcon, UsersIcon } from 'lucide-react';
import { motion } from 'motion/react';

export const menuItems = [] as const;

export default function Welcome({ demoLink }: { demoLink: string }) {
  const { appName } = usePage<SharedData>().props;
  const { t } = useTranslations();
  return (
    <>
      <Head title={t('Effortless Church Administration')} />
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
        {/* Demo Access Section */}
        <motion.section
          initial={{ opacity: 0, y: 20 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          transition={{ duration: 0.6, delay: 0.1 }}
          className="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-20"
        >
          <div className="mx-auto max-w-4xl text-center">
            <h2 className="mb-6 text-3xl font-bold text-gray-800">{t('Try ChurchesAdmin Today')}</h2>
            <p className="mb-8 text-lg text-gray-600">
              {t('Experience our platform firsthand with our demo church. No signup required - just log in and explore all features.')}
            </p>

            <div className="mx-auto max-w-md rounded-lg bg-white p-6 shadow-lg">
              <h3 className="mb-4 text-xl font-semibold text-gray-800">{t('Demo Login Credentials')}</h3>
              <div className="space-y-3 text-left">
                <div className="flex justify-between">
                  <span className="font-medium text-gray-600">{t('Email:')}</span>
                  <span className="font-mono text-gray-800">demo@churchesadmin.com</span>
                </div>
                <div className="flex justify-between">
                  <span className="font-medium text-gray-600">{t('Password:')}</span>
                  <span className="font-mono text-gray-800">Demo123</span>
                </div>
              </div>
              <div className="mt-6">
                <Button variant="brand" className="w-full">
                  <a href={demoLink} target="_blank" rel="noopener noreferrer">
                    {t('Access Demo Church')}
                  </a>
                </Button>
              </div>
            </div>
          </div>
        </motion.section>
        <motion.section
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.6 }}
          className="mx-auto max-w-3xl px-6 py-24 text-center"
        >
          <div className="my-2 flex flex-col items-center">
            <h1 className="my-2 w-2xl text-center text-5xl font-semibold">
              {t('Effortless Church')} <span className="text-brand">{t('Administration')}</span>
            </h1>
            <p className="max-w-2xl py-10 text-center text-lg">
              {t(
                'ChurchesAdmin helps you manage your church with ease, while also keeping track of offerings, members and missionaries - all in one beautiful dashboard.',
              )}
            </p>

            <CtaButton />
          </div>
        </motion.section>

        {/* Features Section */}
        <section className="bg-brand/10 px-6 py-20">
          <div className="mx-auto grid max-w-5xl gap-10 text-center md:grid-cols-3">
            <motion.div initial={{ opacity: 0, y: 20 }} whileInView={{ opacity: 1, y: 0 }} viewport={{ once: true }} transition={{ delay: 0.1 }}>
              <DollarSignIcon className="text-brand mx-auto mb-4" size={32} />
              <h3 className="mb-2 text-xl font-semibold">{t('Clergy Payroll Made Easy')}</h3>
              <p className="text-gray-600">{t('Handle housing allowances, tax complexities, and direct deposits - all automated and accurate.')}</p>
            </motion.div>

            <motion.div initial={{ opacity: 0, y: 20 }} whileInView={{ opacity: 1, y: 0 }} viewport={{ once: true }} transition={{ delay: 0.2 }}>
              <UsersIcon className="text-brand mx-auto mb-4" size={32} />
              <h3 className="mb-2 text-xl font-semibold">{t('Member Management')}</h3>
              <p className="text-gray-600">{t('Keep detailed records, contact info, and family ties for every member of your congregation.')}</p>
            </motion.div>

            <motion.div initial={{ opacity: 0, y: 20 }} whileInView={{ opacity: 1, y: 0 }} viewport={{ once: true }} transition={{ delay: 0.3 }}>
              <GlobeIcon className="text-brand mx-auto mb-4" size={32} />
              <h3 className="mb-2 text-xl font-semibold">{t('Missionary Oversight')}</h3>
              <p className="text-gray-600">{t('Track assignments, support levels, communication logs, and prayer requests with ease.')}</p>
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
          <h2 className="mb-10 text-3xl font-bold">{t('What Pastors Are Saying')}</h2>
          <div className="mx-auto max-w-3xl space-y-10">
            <blockquote className="text-gray-700 italic">
              {t('ChurchesAdmin gave us peace of mind. Payroll is no longer a burden, and our team finally has time to focus on ministry.”')}
              <br />
              <span className="mt-3 block font-semibold">- {t('Pastor Maria, Lighthouse Church')}</span>
            </blockquote>
            <blockquote className="text-gray-700 italic">
              {t("“I love how we can manage both members and missionaries from the same place. It's truly built for the church.”")}
              <br />
              <span className="mt-3 block font-semibold">- {t('Rev. Daniel, Hope Mission Center')}</span>
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
          <h2 className="mb-4 text-3xl font-bold">{t('Ready to See ChurchesAdmin in Action?')}</h2>
          <p className="mb-6 text-lg">{t("Let us walk you through how ChurchesAdmin can serve your ministry's needs.")}</p>
          <CtaButton />
        </motion.section>

        {/* Footer */}
        <footer className="px-6 py-10 text-center text-sm text-gray-500">
          <div>
            <p>{t('Contact us: :email | :phone', { email: 'support@churchesadministration.com', phone: '(000) 000-0000' })}</p>
            <p>
              &copy; {new Date().getFullYear()} {t('ChurchesAdmin. All rights reserved.')}
            </p>
          </div>
        </footer>
      </main>
    </>
  );
}

function CtaButton() {
  const { t } = useTranslations();
  return (
    <Button variant="brand">
      <Link href={route('root.home')} prefetch>
        {t('Schedule a demo')}
      </Link>
    </Button>
  );
}
