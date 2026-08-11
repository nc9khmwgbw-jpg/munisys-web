"use client";

import React, { useRef, useEffect } from 'react';
import { Canvas, useFrame } from '@react-three/fiber';
import { Float, Environment, Lightformer, Sparkles, MeshTransmissionMaterial } from '@react-three/drei';
import * as THREE from 'three';

// ── COMPONENT: THE INFINITY LOOP (L'Anneau d'Infinité) ────
// Abandons all spherical/globe concepts. Uses a continuous Trefoil Knot (Infinity)
// to represent flawless Integration, CI/CD, and infinite IT architecture.
function InfinityIntegrationLoop({ isTextRight }) {
  const groupRef = useRef();
  const innerRef = useRef();
  
  // Custom mouse tracker to bypass pointer-events: none on the Canvas container
  const mouse = useRef({ x: 0, y: 0 });

  const [isMobile, setIsMobile] = React.useState(false);
  const [isTablet, setIsTablet] = React.useState(false);

  React.useEffect(() => {
    const handleMouseMove = (e) => {
      // Normalize mouse coordinates to -1 to +1
      mouse.current.x = (e.clientX / window.innerWidth) * 2 - 1;
      mouse.current.y = -(e.clientY / window.innerHeight) * 2 + 1;
    };
    
    const handleResize = () => {
      const isPreviewMobile = document.body.classList.contains('preview-mobile');
      const isPreviewTablet = document.body.classList.contains('preview-tablet');
      setIsMobile(isPreviewMobile || window.innerWidth <= 600);
      setIsTablet(isPreviewTablet || (window.innerWidth <= 1024 && window.innerWidth > 600));
    };
    
    handleResize();
    window.addEventListener('mousemove', handleMouseMove);
    window.addEventListener('resize', handleResize);
    
    // Also listen for a custom event we might dispatch when preview mode changes
    const observer = new MutationObserver(handleResize);
    observer.observe(document.body, { attributes: true, attributeFilter: ['class'] });
    
    return () => {
      window.removeEventListener('mousemove', handleMouseMove);
      window.removeEventListener('resize', handleResize);
      observer.disconnect();
    };
  }, []);

  useFrame((state, delta) => {
    if (groupRef.current) {
      // Majestic, slow overall rotation of the entire structure
      groupRef.current.rotation.y += delta * 0.1;
      groupRef.current.rotation.x += delta * 0.05;
      
      // Smooth, exaggerated parallax tracking with the mouse
      const targetRotX = (mouse.current.y * Math.PI) / 6;
      const targetRotY = (mouse.current.x * Math.PI) / 5;
      groupRef.current.rotation.x = THREE.MathUtils.lerp(groupRef.current.rotation.x, targetRotX, 0.05);
      groupRef.current.rotation.y = THREE.MathUtils.lerp(groupRef.current.rotation.y, targetRotY, 0.05);
      
      // UI Layout positioning - Responsive
      let targetX = isTextRight ? -4.5 : 4.5;
      let targetY = 0;
      let targetScale = 1.2;

      if (isMobile) {
        targetX = 0; // perfectly centered horizontally
        targetY = 2.5; // pushed higher up
        targetScale = 0.45; // much smaller
      } else if (isTablet) {
        targetX = 0; // perfectly centered horizontally
        targetY = 2.0; // pushed higher up
        targetScale = 0.65; // much smaller
      }

      groupRef.current.position.x = THREE.MathUtils.lerp(groupRef.current.position.x, targetX, 0.05);
      groupRef.current.position.y = THREE.MathUtils.lerp(groupRef.current.position.y, targetY, 0.05);
      
      const currentScale = groupRef.current.scale.x;
      const newScale = THREE.MathUtils.lerp(currentScale, targetScale, 0.05);
      groupRef.current.scale.set(newScale, newScale, newScale);
    }
    
    // The inner core spins slightly faster to create dynamic internal refraction
    if (innerRef.current) {
      innerRef.current.rotation.x -= delta * 0.05;
    }
  });

  return (
    <group ref={groupRef} position={[4.5, 0, -2]} scale={1.2}>
      <Float speed={1.5} rotationIntensity={0.2} floatIntensity={0.3}>
        
        <group ref={innerRef}>
          {/* 1. DEEP CORE: The Data Stream */}
          {/* A solid, intense blue line running through the exact center of the glass loop */}
          <mesh>
            <torusKnotGeometry args={[2.2, 0.06, 256, 16, 2, 3]} />
            <meshBasicMaterial color="#1d4e79" />
          </mesh>
          
          {/* 2. THE NEURAL CAGE: Wireframe Structure */}
          {/* A geometric structure representing infrastructure and code, surrounding the data stream */}
          <mesh>
            <torusKnotGeometry args={[2.2, 0.35, 256, 32, 2, 3]} />
            <meshBasicMaterial color="#922b55" wireframe transparent opacity={0.35} />
          </mesh>
        </group>

        {/* 3. THE INTEGRATION SHELL: Solid Refractive Glass */}
        {/* A massive glass tube that perfectly encases the inner structures.
            As it rotates, the thick glass acts as a complex lens, warping and 
            magnifying the wireframe and data stream inside it in real-time. */}
        <mesh>
          <torusKnotGeometry args={[2.2, 0.7, 256, 64, 2, 3]} />
          <MeshTransmissionMaterial 
            transmission={1}          // 100% Glass property
            thickness={3.0}           // Massive volume for deep refraction
            roughness={0.02}          // Ultra-polished surface
            ior={1.5}                 // Index of Refraction (Standard Glass)
            chromaticAberration={0.04}// High-end rainbow edge dispersion
            clearcoat={1}
            color="#ffffff"
            background={new THREE.Color('#ffffff')} // Perfect blending with the page
          />
        </mesh>

        {/* 4. ARCHITECTURAL BOUNDARIES (Outer Halos) */}
        <mesh rotation={[Math.PI / 3, 0, 0]}>
          <torusGeometry args={[4.2, 0.002, 16, 128]} />
          <meshBasicMaterial color="#1d4e79" transparent opacity={0.5} />
        </mesh>
        <mesh rotation={[-Math.PI / 4, Math.PI / 4, 0]}>
          <torusGeometry args={[4.5, 0.004, 16, 128]} />
          <meshBasicMaterial color="#83a5c2" transparent opacity={0.3} />
        </mesh>

        {/* 5. THE DATA SWARM */}
        <Sparkles count={1200} scale={10} size={1.5} speed={0.3} opacity={0.5} color="#1d4e79" />
        <Sparkles count={400} scale={12} size={2.5} speed={0.15} opacity={0.4} color="#922b55" />

      </Float>
    </group>
  );
}

// ── Lightweight SVG fallback for mobile / low-power devices ──
function DataCoreFallback() {
  return (
    <div style={{
      position: 'absolute', top: 0, left: 0, width: '100%', height: '100%',
      zIndex: 0, pointerEvents: 'none', display: 'flex',
      alignItems: 'center', justifyContent: 'center',
      overflow: 'hidden',
    }}>
      {/* Static gradient orb — zero GPU cost */}
      <div style={{
        width: '280px', height: '280px', borderRadius: '50%',
        background: 'radial-gradient(circle at 35% 35%, rgba(131,165,194,0.35) 0%, rgba(29,78,121,0.25) 40%, rgba(146,43,85,0.15) 70%, transparent 100%)',
        filter: 'blur(20px)',
        animation: 'orb-pulse 4s ease-in-out infinite alternate',
      }} />
      <style>{`
        @keyframes orb-pulse {
          from { transform: scale(0.95) rotate(-5deg); opacity: 0.7; }
          to   { transform: scale(1.05) rotate(5deg);  opacity: 1; }
        }
      `}</style>
    </div>
  );
}

export default function DataCore({ isTextRight }) {
  const [isMobile, setIsMobile] = React.useState(false);

  React.useEffect(() => {
    const check = () => {
      const mobile = window.matchMedia('(pointer: coarse)').matches || window.innerWidth < 768;
      setIsMobile(mobile);
    };
    check();
    window.addEventListener('resize', check, { passive: true });
    return () => window.removeEventListener('resize', check);
  }, []);

  // On mobile/touch devices skip the heavy WebGL canvas entirely
  if (isMobile) {
    return <DataCoreFallback />;
  }

  return (
    <div style={{ position: 'absolute', top: 0, left: 0, width: '100%', height: '100%', zIndex: 0, pointerEvents: 'none' }} aria-hidden="true">
      <Canvas camera={{ position: [0, 0, 16], fov: 45 }}>
        {/* Studio lighting */}
        <ambientLight intensity={1.5} />
        <directionalLight position={[10, 10, 10]} intensity={3.5} color="#ffffff" />
        <directionalLight position={[-10, -5, 5]} intensity={6} color="#922b55" />
        <directionalLight position={[0, -10, -5]} intensity={5} color="#83a5c2" />
        <Environment resolution={256}>
          <group rotation={[-Math.PI / 2, 0, 0]}>
            <Lightformer intensity={4} rotation-x={Math.PI / 2} position={[0, 5, -9]} scale={[10, 10, 1]} />
            <Lightformer intensity={2} rotation-y={Math.PI / 2} position={[-5, 1, -1]} scale={[10, 2, 1]} />
            <Lightformer intensity={2} rotation-y={-Math.PI / 2} position={[10, 1, 0]} scale={[20, 2, 1]} />
            <Lightformer type="ring" intensity={2} rotation-y={Math.PI / 2} position={[-0.1, -1, -5]} scale={10} />
          </group>
        </Environment>
        
        <InfinityIntegrationLoop isTextRight={isTextRight} />
      </Canvas>
    </div>
  );
}
