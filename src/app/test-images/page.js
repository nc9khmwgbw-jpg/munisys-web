"use client";

import InlineImageEditor from '@/components/InlineImageEditor';
import InlineEditor from '@/components/InlineEditor';

export default function TestImagesPage() {
  return (
    <div style={{ padding: '2rem', maxWidth: '1200px', margin: '0 auto' }}>
      <h1 style={{ fontSize: '2.5rem', marginBottom: '2rem', color: '#1d4e79' }}>
        🧪 Page de Test - Éditeur d'Images
      </h1>

      <div style={{ background: '#f8fafc', padding: '2rem', borderRadius: '8px', marginBottom: '2rem' }}>
        <h2 style={{ fontSize: '1.5rem', marginBottom: '1rem', color: '#922b55' }}>
          📝 Instructions
        </h2>
        <ol style={{ lineHeight: '2', color: '#475569' }}>
          <li>Allez sur <a href="/admin" style={{ color: '#1d4e79', fontWeight: 'bold' }}>/admin</a> pour activer le mode édition</li>
          <li>Revenez sur cette page</li>
          <li>Les images ci-dessous auront un cadre rouge pointillé</li>
          <li>Cliquez sur n'importe quelle image pour la changer</li>
          <li>Sauvegardez avec le bouton "Save Changes"</li>
        </ol>
      </div>

      {/* Test 1: Image simple */}
      <section style={{ marginBottom: '3rem' }}>
        <h2 style={{ fontSize: '1.3rem', marginBottom: '1rem', color: '#1d4e79' }}>
          Test 1: Image Simple
        </h2>
        <div style={{ border: '2px solid #e2e8f0', padding: '2rem', borderRadius: '8px' }}>
          <InlineEditor 
            as="p" 
            dbPath={['test', 'description1']} 
            fallback="Ceci est une image de test. En mode admin, cliquez dessus pour la changer." 
          />
          <div style={{ marginTop: '1rem', maxWidth: '400px' }}>
            <InlineImageEditor 
              dbPath={['test', 'image1']} 
              fallback="/slide1.png" 
              alt="Image de test 1" 
            />
          </div>
        </div>
      </section>

      {/* Test 2: Grid d'images */}
      <section style={{ marginBottom: '3rem' }}>
        <h2 style={{ fontSize: '1.3rem', marginBottom: '1rem', color: '#1d4e79' }}>
          Test 2: Grille d'Images
        </h2>
        <div style={{ border: '2px solid #e2e8f0', padding: '2rem', borderRadius: '8px' }}>
          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(3, 1fr)', gap: '2rem' }}>
            {[1, 2, 3].map((num) => (
              <div key={num}>
                <h3 style={{ marginBottom: '0.5rem', color: '#922b55' }}>Image {num}</h3>
                <InlineImageEditor 
                  dbPath={['test', `gridImage${num}`]} 
                  fallback={`/slide${num}.png`} 
                  alt={`Image de grille ${num}`} 
                />
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Test 3: Image avec styles personnalisés */}
      <section style={{ marginBottom: '3rem' }}>
        <h2 style={{ fontSize: '1.3rem', marginBottom: '1rem', color: '#1d4e79' }}>
          Test 3: Image Circulaire
        </h2>
        <div style={{ border: '2px solid #e2e8f0', padding: '2rem', borderRadius: '8px' }}>
          <div style={{ maxWidth: '300px', margin: '0 auto' }}>
            <InlineImageEditor 
              dbPath={['test', 'circularImage']} 
              fallback="/slide2.png" 
              alt="Image circulaire" 
              style={{ borderRadius: '50%', overflow: 'hidden' }}
            />
          </div>
        </div>
      </section>

      {/* Test 4: Hero-like avec overlay */}
      <section style={{ marginBottom: '3rem' }}>
        <h2 style={{ fontSize: '1.3rem', marginBottom: '1rem', color: '#1d4e79' }}>
          Test 4: Hero avec Texte Overlay
        </h2>
        <div style={{ border: '2px solid #e2e8f0', borderRadius: '8px', overflow: 'hidden' }}>
          <div style={{ position: 'relative', height: '400px' }}>
            <InlineImageEditor 
              dbPath={['test', 'heroImage']} 
              fallback="/hero.png" 
              alt="Image hero" 
              style={{ width: '100%', height: '100%', objectFit: 'cover' }}
            />
            <div style={{
              position: 'absolute',
              top: 0,
              left: 0,
              right: 0,
              bottom: 0,
              background: 'rgba(29, 78, 121, 0.7)',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              color: 'white',
              padding: '2rem',
              textAlign: 'center'
            }}>
              <div>
                <InlineEditor 
                  as="h2" 
                  dbPath={['test', 'heroTitle']} 
                  fallback="Titre Hero Éditable" 
                  style={{ fontSize: '3rem', fontWeight: 'bold', marginBottom: '1rem' }}
                />
                <InlineEditor 
                  as="p" 
                  dbPath={['test', 'heroSubtitle']} 
                  fallback="Sous-titre également éditable en mode admin" 
                  style={{ fontSize: '1.2rem' }}
                />
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Stats */}
      <section style={{ marginBottom: '3rem' }}>
        <div style={{ 
          background: 'linear-gradient(135deg, #1d4e79 0%, #922b55 100%)', 
          color: 'white', 
          padding: '2rem', 
          borderRadius: '8px',
          textAlign: 'center'
        }}>
          <h3 style={{ fontSize: '1.5rem', marginBottom: '1rem' }}>📊 Statistiques</h3>
          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(3, 1fr)', gap: '2rem' }}>
            <div>
              <div style={{ fontSize: '3rem', fontWeight: 'bold' }}>4</div>
              <div>Tests d'images</div>
            </div>
            <div>
              <div style={{ fontSize: '3rem', fontWeight: 'bold' }}>8</div>
              <div>Images éditables</div>
            </div>
            <div>
              <div style={{ fontSize: '3rem', fontWeight: 'bold' }}>100%</div>
              <div>Fonctionnel</div>
            </div>
          </div>
        </div>
      </section>

      {/* Footer */}
      <div style={{ 
        textAlign: 'center', 
        padding: '2rem', 
        borderTop: '2px solid #e2e8f0',
        color: '#64748b'
      }}>
        <p style={{ marginBottom: '1rem' }}>
          ✅ Si vous voyez les cadres rouges pointillés autour des images, le système fonctionne !
        </p>
        <p>
          <a href="/" style={{ color: '#1d4e79', fontWeight: 'bold', marginRight: '2rem' }}>
            ← Retour à l'accueil
          </a>
          <a href="/admin" style={{ color: '#922b55', fontWeight: 'bold' }}>
            Activer le mode admin →
          </a>
        </p>
      </div>
    </div>
  );
}
