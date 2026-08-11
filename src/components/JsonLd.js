"use client";

import React from 'react';

/**
 * Renders a JSON-LD script tag for SEO.
 * @param {Object} props.data - The JSON-LD object to be serialized.
 */
export default function JsonLd({ data }) {
  if (!data) return null;

  return (
    <script
      type="application/ld+json"
      dangerouslySetInnerHTML={{ __html: JSON.stringify(data) }}
    />
  );
}
