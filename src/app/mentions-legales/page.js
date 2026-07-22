"use client";

import React from 'react';
import InlineEditor from '../../components/InlineEditor';
import { useCMS } from '../../components/CMSProvider';

export default function MentionsLegales() {
  const { data } = useCMS();
  // Using standard styling similar to the contact page but adapted for text-heavy content
  return (
    <div style={{ paddingTop: '120px', paddingBottom: '60px', minHeight: '100vh', backgroundColor: '#0f172a', color: '#f8fafc' }}>
      <div style={{ maxWidth: '800px', margin: '0 auto', padding: '0 20px', fontFamily: 'var(--font-primary, sans-serif)' }}>
        
        <h1 style={{ fontSize: '3.5rem', fontWeight: 800, color: '#f8fafc', marginBottom: '20px', letterSpacing: '-0.02em' }}>
          <InlineEditor as="span" dbPath={['legal', 'mentions', 'title']} fallback="Mentions Légales" />
        </h1>
        <div style={{ width: '60px', height: '4px', background: 'linear-gradient(90deg, #38bdf8, #818cf8)', marginBottom: '50px', borderRadius: '2px' }}></div>

        <div style={{ display: 'flex', flexDirection: 'column', gap: '40px', lineHeight: '1.8', fontSize: '1.1rem', color: '#94a3b8' }}>
          <section>
            <h2 style={{ fontSize: '1.5rem', fontWeight: 600, color: '#f8fafc', marginBottom: '15px', display: 'flex', alignItems: 'center', gap: '12px' }}>
              <span style={{ color: '#38bdf8' }}>01.</span>
              <InlineEditor as="span" dbPath={['legal', 'mentions', 'editeur_title']} fallback="Éditeur du site" />
            </h2>
            <div style={{ paddingLeft: '40px' }}>
              <InlineEditor as="p" dbPath={['legal', 'mentions', 'editeur_text']} fallback="Le site Munisys.net est édité par la société Munisys, spécialisée dans l'architecture et l'intégration des technologies de l'information." />
            </div>
          </section>

          <section>
            <h2 style={{ fontSize: '1.5rem', fontWeight: 600, color: '#f8fafc', marginBottom: '15px', display: 'flex', alignItems: 'center', gap: '12px' }}>
              <span style={{ color: '#38bdf8' }}>02.</span>
              <InlineEditor as="span" dbPath={['legal', 'mentions', 'hebergement_title']} fallback="Hébergement" />
            </h2>
            <div style={{ paddingLeft: '40px' }}>
              <InlineEditor as="p" dbPath={['legal', 'mentions', 'hebergement_text']} fallback="L'hébergement de ce site est assuré par nos serveurs de production sécurisés." />
            </div>
          </section>

          <section>
            <h2 style={{ fontSize: '1.5rem', fontWeight: 600, color: '#f8fafc', marginBottom: '15px', display: 'flex', alignItems: 'center', gap: '12px' }}>
              <span style={{ color: '#38bdf8' }}>03.</span>
              <InlineEditor as="span" dbPath={['legal', 'mentions', 'pi_title']} fallback="Propriété Intellectuelle" />
            </h2>
            <div style={{ paddingLeft: '40px' }}>
              <InlineEditor as="p" dbPath={['legal', 'mentions', 'pi_text']} fallback="L'ensemble de ce site relève des législations marocaines et internationales sur le droit d'auteur et la propriété intellectuelle. Tous les droits de reproduction sont réservés, y compris pour les documents iconographiques et photographiques." />
            </div>
          </section>
        </div>

      </div>
    </div>
  );
}
