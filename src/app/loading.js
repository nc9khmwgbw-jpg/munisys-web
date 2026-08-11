export default function Loading() {
  return (
    <div style={{
      minHeight: '80vh',
      display: 'flex',
      flexDirection: 'column',
      alignItems: 'center',
      justifyContent: 'center',
      gap: '2rem',
      padding: '4rem 2rem',
      fontFamily: "'Outfit', 'Montserrat', sans-serif",
    }}>
      {/* Animated logo / spinner */}
      <div style={{
        width: '56px',
        height: '56px',
        position: 'relative',
        animation: 'mns-spin 1.2s linear infinite',
      }}>
        <svg viewBox="0 0 100 100" fill="none" width="56" height="56">
          <polygon points="10,20 40,50 10,80" fill="#83a5c2" opacity="0.9" />
          <polygon points="10,20 40,50 40,20" fill="#1d4e79" opacity="0.9" />
          <polygon points="40,50 70,20 70,80" fill="#922b55" opacity="0.9" />
          <polygon points="70,20 100,20 70,50" fill="#1d4e79" opacity="0.9" />
        </svg>
      </div>

      {/* Skeleton blocks */}
      <div style={{ width: '100%', maxWidth: '800px', display: 'flex', flexDirection: 'column', gap: '1rem' }}>
        {/* Title skeleton */}
        <div style={{
          height: '40px', borderRadius: '8px', width: '60%', margin: '0 auto',
          background: 'linear-gradient(90deg, #e2e8f0 25%, #f1f5f9 50%, #e2e8f0 75%)',
          backgroundSize: '200% 100%',
          animation: 'mns-shimmer 1.5s infinite',
        }} />
        {/* Subtitle skeleton */}
        <div style={{
          height: '20px', borderRadius: '6px', width: '45%', margin: '0 auto',
          background: 'linear-gradient(90deg, #e2e8f0 25%, #f1f5f9 50%, #e2e8f0 75%)',
          backgroundSize: '200% 100%',
          animation: 'mns-shimmer 1.5s infinite 0.2s',
        }} />
      </div>

      {/* Card skeletons */}
      <div style={{
        display: 'grid',
        gridTemplateColumns: 'repeat(auto-fill, minmax(280px, 1fr))',
        gap: '1.5rem',
        width: '100%',
        maxWidth: '1200px',
      }}>
        {[0, 1, 2].map((i) => (
          <div key={i} style={{
            borderRadius: '16px',
            overflow: 'hidden',
            border: '1px solid #e2e8f0',
          }}>
            <div style={{
              height: '200px',
              background: 'linear-gradient(90deg, #e2e8f0 25%, #f1f5f9 50%, #e2e8f0 75%)',
              backgroundSize: '200% 100%',
              animation: `mns-shimmer 1.5s infinite ${i * 0.15}s`,
            }} />
            <div style={{ padding: '1.5rem', display: 'flex', flexDirection: 'column', gap: '0.75rem' }}>
              <div style={{
                height: '12px', borderRadius: '4px', width: '30%',
                background: 'linear-gradient(90deg, #e2e8f0 25%, #f1f5f9 50%, #e2e8f0 75%)',
                backgroundSize: '200% 100%',
                animation: `mns-shimmer 1.5s infinite ${i * 0.15 + 0.1}s`,
              }} />
              <div style={{
                height: '20px', borderRadius: '6px', width: '85%',
                background: 'linear-gradient(90deg, #e2e8f0 25%, #f1f5f9 50%, #e2e8f0 75%)',
                backgroundSize: '200% 100%',
                animation: `mns-shimmer 1.5s infinite ${i * 0.15 + 0.2}s`,
              }} />
              <div style={{
                height: '16px', borderRadius: '5px', width: '70%',
                background: 'linear-gradient(90deg, #e2e8f0 25%, #f1f5f9 50%, #e2e8f0 75%)',
                backgroundSize: '200% 100%',
                animation: `mns-shimmer 1.5s infinite ${i * 0.15 + 0.3}s`,
              }} />
            </div>
          </div>
        ))}
      </div>

      <style>{`
        @keyframes mns-shimmer {
          0% { background-position: 200% 0; }
          100% { background-position: -200% 0; }
        }
        @keyframes mns-spin {
          0% { transform: rotate(0deg); }
          100% { transform: rotate(360deg); }
        }
        [data-theme="dark"] .mns-skeleton-block {
          background: linear-gradient(90deg, #1e293b 25%, #334155 50%, #1e293b 75%) !important;
        }
      `}</style>
    </div>
  );
}
