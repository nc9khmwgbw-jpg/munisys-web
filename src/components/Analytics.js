"use client";
import Script from 'next/script';

export default function Analytics() {
  // Use environment variables for the domain so it can be easily configured
  // For Plausible, you just need the domain name
  const domain = process.env.NEXT_PUBLIC_ANALYTICS_DOMAIN || "munisys.net";
  const scriptUrl = process.env.NEXT_PUBLIC_ANALYTICS_SCRIPT || "https://plausible.io/js/script.js";

  // If we are in development mode, you might want to disable tracking
  const isDev = process.env.NODE_ENV === 'development';

  if (isDev) return null;

  return (
    <Script
      defer
      data-domain={domain}
      src={scriptUrl}
      strategy="afterInteractive"
    />
  );
}
