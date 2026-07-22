"use client";

import React from 'react';
import InlineEditor from '../../components/InlineEditor';
import { useCMS } from '../../components/CMSProvider';

export default function PolitiqueConfidentialite() {
  const { data } = useCMS();
  
  return (
    <div style={{ paddingTop: '120px', paddingBottom: '60px', minHeight: '100vh', backgroundColor: '#0f172a', color: '#f8fafc' }}>
      <div style={{ maxWidth: '800px', margin: '0 auto', padding: '0 20px', fontFamily: 'var(--font-primary, sans-serif)' }}>
        
        <h1 style={{ fontSize: '3.5rem', fontWeight: 800, color: '#f8fafc', marginBottom: '20px', letterSpacing: '-0.02em' }}>
          <InlineEditor as="span" dbPath={['legal', 'privacy', 'title']} fallback="Politique de Confidentialité" />
        </h1>
        <div style={{ width: '60px', height: '4px', background: 'linear-gradient(90deg, #38bdf8, #818cf8)', marginBottom: '50px', borderRadius: '2px' }}></div>

        <div style={{ display: 'flex', flexDirection: 'column', gap: '40px', lineHeight: '1.8', fontSize: '1.1rem', color: '#94a3b8' }}>
          <section>
            <h2 style={{ fontSize: '1.5rem', fontWeight: 600, color: '#f8fafc', marginBottom: '15px', display: 'flex', alignItems: 'center', gap: '12px' }}>
              <span style={{ color: '#38bdf8' }}>01.</span>
              <InlineEditor as="span" dbPath={['legal', 'privacy', 'collect_title']} fallback="Collecte des Données" />
            </h2>
            <div style={{ paddingLeft: '40px' }}>
              <InlineEditor as="p" dbPath={['legal', 'privacy', 'collect_text']} fallback="Nous collectons uniquement les informations que vous nous fournissez volontairement via notre formulaire de contact (nom, email, entreprise, téléphone, message). Ces données sont utilisées exclusivement pour répondre à vos demandes professionnelles." />
            </div>
          </section>

          <section>
            <h2 style={{ fontSize: '1.5rem', fontWeight: 600, color: '#f8fafc', marginBottom: '15px', display: 'flex', alignItems: 'center', gap: '12px' }}>
              <span style={{ color: '#38bdf8' }}>02.</span>
              <InlineEditor as="span" dbPath={['legal', 'privacy', 'cookies_title']} fallback="Utilisation des Cookies" />
            </h2>
            <div style={{ paddingLeft: '40px' }}>
              <InlineEditor as="p" dbPath={['legal', 'privacy', 'cookies_text']} fallback="Notre site utilise des cookies techniques pour son fonctionnement de base. Avec votre consentement, nous pouvons également utiliser des cookies d'analyse pour comprendre comment notre site est utilisé et l'améliorer." />
            </div>
          </section>

          <section>
            <h2 style={{ fontSize: '1.5rem', fontWeight: 600, color: '#f8fafc', marginBottom: '15px', display: 'flex', alignItems: 'center', gap: '12px' }}>
              <span style={{ color: '#38bdf8' }}>03.</span>
              <InlineEditor as="span" dbPath={['legal', 'privacy', 'rights_title']} fallback="Vos Droits" />
            </h2>
            <div style={{ paddingLeft: '40px' }}>
              <InlineEditor as="p" dbPath={['legal', 'privacy', 'rights_text']} fallback="Conformément à la réglementation en vigueur, vous disposez d'un droit d'accès, de rectification, de suppression et de portabilité de vos données. Pour exercer ces droits, vous pouvez nous contacter directement." />
            </div>
          </section>
        </div>

      </div>
    </div>
  );
}
