import type { SharedData } from '@/types';
import { usePage } from '@inertiajs/react';

export function useTenantFeature() {
  const {
    props: { features },
  } = usePage<SharedData>();

  return features;
}
