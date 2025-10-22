import type { ChurchFeature } from '@/enums/ChurchFeature';
import type { SharedData } from '@/types';
import { usePage } from '@inertiajs/react';

export function useFeatureFlag(feature: ChurchFeature) {
  const {
    props: { features },
  } = usePage<SharedData>();

  return {
    status: features[feature] ?? false,
  };
}
