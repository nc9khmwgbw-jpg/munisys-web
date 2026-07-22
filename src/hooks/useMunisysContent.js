"use client";

import useSWR from 'swr';

const fetcher = async (url) => {
  const res = await fetch(url);
  const json = await res.json();
  if (!json.success) {
    return {}; // Retourne un objet vide si erreur (ex: pas encore de données en DB)
  }
  return json.data;
};

const EMPTY_OBJ = {};

export const useMunisysContent = (pageName) => {
  const { data, error, isLoading, mutate } = useSWR(
    pageName ? `/api/munisys/content/${pageName}` : null, 
    fetcher,
    {
      revalidateOnFocus: false,
      dedupingInterval: 60000,
      fallbackData: EMPTY_OBJ 
    }
  );

  return {
    content: data || EMPTY_OBJ,
    isLoading,
    isError: error,
    refreshContent: mutate
  };
};
