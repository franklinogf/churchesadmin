import { Button } from '@/components/ui/button';
import { NavigationMenu, NavigationMenuItem, NavigationMenuLink, NavigationMenuList } from '@/components/ui/navigation-menu';
import type { SharedData } from '@/types';
import { Head, Link, usePage } from '@inertiajs/react';

import HomeController from '@/actions/App/Http/Controllers/Root/HomeController';
import { DollarSignIcon, GlobeIcon, TvIcon, UsersIcon } from 'lucide-react';
import { motion } from 'motion/react';
import { useTranslation } from 'react-i18next';

export const menuItems = [] as const;

export default function Welcome({ demoLink }: { demoLink: string | null }) {
  const { appName } = usePage<SharedData>().props;
  const { t: tPages } = useTranslation('pages');
  return (
    <>
      <Head title={tPages(($) => $.welcome.effortlessChurchAdministration)} />
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
        {demoLink && (
          <motion.section
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.6, delay: 0.1 }}
            className="bg-linear-to-r from-blue-50 to-indigo-50 px-6 py-20"
          >
            <div className="mx-auto max-w-4xl text-center">
              <h2 className="mb-6 text-3xl font-bold text-gray-800">{tPages(($) => $.welcome.tryChurchesadminToday)}</h2>
              <p className="mb-8 text-lg text-gray-600">{tPages(($) => $.welcome.experienceOurPlatformFirsthandWithOurDemoChurchNo)}</p>

              <div className="mx-auto max-w-md rounded-lg bg-white p-6 shadow-lg">
                <h3 className="mb-4 text-xl font-semibold text-gray-800">{tPages(($) => $.welcome.demoLoginCredentials)}</h3>
                <div className="space-y-3 text-left">
                  <div className="flex justify-between">
                    <span className="font-medium text-gray-600">{tPages(($) => $.welcome.email)}</span>
                    <span className="font-mono text-gray-800">admin@churchesadministration.com</span>
                  </div>
                  <div className="flex justify-between">
                    <span className="font-medium text-gray-600">{tPages(($) => $.welcome.password)}</span>
                    <span className="font-mono text-gray-800">Password123</span>
                  </div>
                </div>
                <div className="mt-6">
                  <Button variant="brand" className="w-full">
                    <a href={demoLink} target="_blank" rel="noopener noreferrer">
                      {tPages(($) => $.welcome.accessDemoChurch)}
                    </a>
                  </Button>
                </div>
              </div>
            </div>
          </motion.section>
        )}
        <motion.section
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.6 }}
          className="mx-auto max-w-3xl px-6 py-24 text-center"
        >
          <div className="my-2 flex flex-col items-center">
            <h1 className="my-2 w-2xl text-center text-5xl font-semibold">
              {tPages(($) => $.welcome.effortlessChurch)} <span className="text-brand">{tPages(($) => $.welcome.administration)}</span>
            </h1>
            <p className="max-w-2xl py-10 text-center text-lg">{tPages(($) => $.welcome.churchesadminHelpsYouManageYourChurchWithEaseWhile)}</p>

            <CtaButton />
          </div>
        </motion.section>

        {/* Features Section */}
        <section className="bg-brand/10 px-6 py-20">
          <div className="mx-auto grid max-w-5xl gap-10 text-center md:grid-cols-3">
            <motion.div initial={{ opacity: 0, y: 20 }} whileInView={{ opacity: 1, y: 0 }} viewport={{ once: true }} transition={{ delay: 0.1 }}>
              <DollarSignIcon className="text-brand mx-auto mb-4" size={32} />
              <h3 className="mb-2 text-xl font-semibold">{tPages(($) => $.welcome.clergyPayrollMadeEasy)}</h3>
              <p className="text-gray-600">{tPages(($) => $.welcome.handleHousingAllowancesTaxComplexitiesAndDirectDepositsAll)}</p>
            </motion.div>

            <motion.div initial={{ opacity: 0, y: 20 }} whileInView={{ opacity: 1, y: 0 }} viewport={{ once: true }} transition={{ delay: 0.2 }}>
              <UsersIcon className="text-brand mx-auto mb-4" size={32} />
              <h3 className="mb-2 text-xl font-semibold">{tPages(($) => $.welcome.memberManagement)}</h3>
              <p className="text-gray-600">{tPages(($) => $.welcome.keepDetailedRecordsContactInfoAndFamilyTiesFor)}</p>
            </motion.div>

            <motion.div initial={{ opacity: 0, y: 20 }} whileInView={{ opacity: 1, y: 0 }} viewport={{ once: true }} transition={{ delay: 0.3 }}>
              <GlobeIcon className="text-brand mx-auto mb-4" size={32} />
              <h3 className="mb-2 text-xl font-semibold">{tPages(($) => $.welcome.missionaryOversight)}</h3>
              <p className="text-gray-600">{tPages(($) => $.welcome.trackAssignmentsSupportLevelsCommunicationLogsAndPrayerRequests)}</p>
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
          <h2 className="mb-10 text-3xl font-bold">{tPages(($) => $.welcome.whatPastorsAreSaying)}</h2>
          <div className="mx-auto max-w-3xl space-y-10">
            <blockquote className="text-gray-700 italic">
              {tPages(($) => $.welcome.churchesadminGaveUsPeaceOfMindPayrollIsNo)}
              <br />
              <span className="mt-3 block font-semibold">- {tPages(($) => $.welcome.pastorMariaLighthouseChurch)}</span>
            </blockquote>
            <blockquote className="text-gray-700 italic">
              {tPages(($) => $.welcome.iLoveHowWeCanManageBothMembersAndMissionaries)}
              <br />
              <span className="mt-3 block font-semibold">- {tPages(($) => $.welcome.revDanielHopeMissionCenter)}</span>
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
          <h2 className="mb-4 text-3xl font-bold">{tPages(($) => $.welcome.readyToSeeChurchesadminInAction)}</h2>
          <p className="mb-6 text-lg">{tPages(($) => $.welcome.letUsWalkYouThroughHowChurchesadminCanServe)}</p>
          <CtaButton />
        </motion.section>

        {/* Footer */}
        <footer className="px-6 py-10 text-center text-sm text-gray-500">
          <div>
            <p>
              {tPages(($) => $.welcome.contactUsEmailPhone, { email: 'support@churchesadministration.com', phone: '689-338-5438 (5435) (5431)' })}
            </p>
            <p>
              &copy; {new Date().getFullYear()} {tPages(($) => $.welcome.churchesadminAllRightsReserved)}
            </p>
          </div>
        </footer>
      </main>
    </>
  );
}

function CtaButton() {
  const { t: tPages } = useTranslation('pages');
  return (
    <Button variant="brand">
      <Link href={HomeController()} prefetch>
        {tPages(($) => $.welcome.scheduleADemo)}
      </Link>
    </Button>
  );
}
