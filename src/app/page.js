"use client";
import Link from 'next/link';

import { useState, useEffect, useCallback, useRef } from 'react';
import { useInView, animate } from 'framer-motion';
import InlineEditor, { InlineImageEditor } from '@/components/InlineEditor';
import { useCMS } from '@/components/CMSProvider';
import FadeIn from '@/components/FadeIn';
import DataCore from '@/components/DataCore';

// ── DRAGGABLE LIST (for Offerings section) ──────────────
function DraggableList({ items, dbPath, fallback }) {
  const { isEditMode, isPreviewMode, updateData } = useCMS();
  const [list, setList] = useState(items);
  const dragIdx = useRef(null);

  useEffect(() => { setList(items); }, [items]);

  const handleDragStart = (idx) => { dragIdx.current = idx; };
  const handleDragOver = (e, idx) => {
    e.preventDefault();
    if (dragIdx.current === null || dragIdx.current === idx) return;
    const newList = [...list];
    const [moved] = newList.splice(dragIdx.current, 1);
    newList.splice(idx, 0, moved);
    dragIdx.current = idx;
    setList(newList);
  };
  const handleDrop = () => { dragIdx.current = null; updateData(dbPath, list); };

  if (!isEditMode || isPreviewMode) {
    return list.map((t, i) => (
      <div className="off-item" key={i}>
        <span className="off-check"><CheckIcon /></span>
        <InlineEditor as="span" dbPath={[...dbPath, i]} fallback={t} />
      </div>
    ));
  }

  return list.map((t, i) => (
    <div
      key={i}
      className="off-item"
      draggable
      onDragStart={() => handleDragStart(i)}
      onDragOver={(e) => handleDragOver(e, i)}
      onDrop={handleDrop}
      style={{ cursor: 'grab', borderRadius: '6px', transition: 'background 0.15s' }}
    >
      {/* Drag Handle */}
      <span title="Glisser pour réordonner" style={{ color: '#922b55', opacity: 0.7, marginRight: '4px', display: 'inline-flex', alignItems: 'center', cursor: 'grab' }}>
        <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor">
          <circle cx="9" cy="6" r="1.5"/><circle cx="9" cy="12" r="1.5"/><circle cx="9" cy="18" r="1.5"/>
          <circle cx="15" cy="6" r="1.5"/><circle cx="15" cy="12" r="1.5"/><circle cx="15" cy="18" r="1.5"/>
        </svg>
      </span>
      <span className="off-check"><CheckIcon /></span>
      <InlineEditor as="span" dbPath={[...dbPath, i]} fallback={t} />
    </div>
  ));
}

const CheckIcon = () => (
  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
    <circle cx="12" cy="12" r="10" /><path d="M8 12l3 3 5-6" />
  </svg>
);

const ArrowIcon = () => (
  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
    <path d="M5 12h14M12 5l7 7-7 7" />
  </svg>
);

// ── DRAGGABLE SERVICES LIST ─────────────────────────────
function DraggableServicesList({ services: initialServices }) {
  const { isEditMode, isPreviewMode, updateData } = useCMS();
  const [list, setList] = useState(initialServices);
  const dragIdx = useRef(null);

  useEffect(() => { setList(initialServices); }, [initialServices]);

  const handleDragStart = (idx) => { dragIdx.current = idx; };
  const handleDragOver = (e, idx) => {
    e.preventDefault();
    if (dragIdx.current === null || dragIdx.current === idx) return;
    const newList = [...list];
    const [moved] = newList.splice(dragIdx.current, 1);
    newList.splice(idx, 0, moved);
    dragIdx.current = idx;
    setList(newList);
  };
  const handleDrop = () => { dragIdx.current = null; updateData(['homepage', 'services'], list); };

  return list.map((s, i) => {
    const content = (
      <>
        {isEditMode && !isPreviewMode && (
          <span title="Glisser pour réordonner" style={{ color: '#922b55', opacity: 0.6, marginRight: '8px', display: 'inline-flex', alignItems: 'center', cursor: 'grab', flexShrink: 0 }}>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
              <circle cx="9" cy="6" r="1.5"/><circle cx="9" cy="12" r="1.5"/><circle cx="9" cy="18" r="1.5"/>
              <circle cx="15" cy="6" r="1.5"/><circle cx="15" cy="12" r="1.5"/><circle cx="15" cy="18" r="1.5"/>
            </svg>
          </span>
        )}
        <div className="srv-title-wrap" style={{ flex: 1 }}>
          <span className="srv-num">{s.num || `0${i + 1}`}</span>
          <InlineEditor as="h3" className="srv-title" dbPath={['homepage', 'services', i, 'title']} fallback={s.title} />
        </div>
        <div className="srv-desc-wrap">
          <InlineEditor as="p" dbPath={['homepage', 'services', i, 'body']} fallback={s.body || s.desc} />
        </div>
        <div className="srv-arrow">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2"><path d="M5 12h14M12 5l7 7-7 7" /></svg>
        </div>
      </>
    );
    if (isEditMode && !isPreviewMode) {
      return (
        <div key={i} className="srv-item" draggable onDragStart={() => handleDragStart(i)} onDragOver={(e) => handleDragOver(e, i)} onDrop={handleDrop} style={{ display: 'flex', alignItems: 'center', cursor: 'grab' }}>
          {content}
        </div>
      );
    }
    return <Link key={i} href={s.link || '/services/'} className="srv-item">{content}</Link>;
  });
}

// ── Default Data ──────────────────────────────────────
const defaultSlides = [
  {
    meta: 'Core Infrastructure',
    title: 'We orchestrate <strong>digital infrastructure</strong> for a unique <strong>experience.</strong>',
    desc: 'Setup, manage, and secure your compute, storage, and networking across data centers and public clouds with our leading data-centric solutions.',
    btnText: 'Our solutions',
    btnLink: '#solutions-section',
    img: 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?q=80&w=2560&auto=format&fit=crop'
  },
  {
    meta: 'Cybersecurity',
    title: 'Protecting your <strong>digital assets</strong> with cutting-edge <strong>security.</strong>',
    desc: 'Secure your network, endpoints, and data against evolving digital threats with zero-trust architectures and proactive monitoring.',
    btnText: 'Discover security',
    btnLink: '/solutions/',
    img: 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?q=80&w=2560&auto=format&fit=crop'
  },
  {
    meta: 'Managed Services',
    title: 'Accelerating your <strong>cloud journey</strong> with seamless <strong>management.</strong>',
    desc: 'Seamless cloud integration and managed services to accelerate your digital transformation journey while ensuring high availability.',
    btnText: 'Cloud services',
    btnLink: '/solutions/',
    img: 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=2560&auto=format&fit=crop'
  }
];

const defaultBentoSolutions = [
  { title: 'Core Infrastructure<br/>Technologies', desc: 'Compute, storage, networking and backup infrastructure across data centers and public clouds with leading data-centric solutions.', btn: 'Read more', img: 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?q=80&w=1200&auto=format&fit=crop', link: '/solutions/', span: 'span-2x3' },
  { title: 'Cybersecurity<br/>Solutions', desc: 'End-to-end security architectures to protect your digital assets from evolving threats.', btn: 'Discover', img: 'https://images.unsplash.com/photo-1614064641938-3bbee52942c7?q=80&w=800&auto=format&fit=crop', link: '/solutions/', span: 'span-2x1' },
  { title: 'Infrastructure<br/>Software', desc: 'Software-defined approaches and robust integrations for modern IT operations.', btn: '', img: 'https://images.unsplash.com/photo-1667372393119-3d4c48d07fc9?q=80&w=800&auto=format&fit=crop', link: '/solutions/', span: 'glass' },
  { title: 'Managed &<br/>Cloud Services', desc: 'Seamless operations across public, private, and hybrid cloud environments.', btn: '', img: 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?q=80&w=800&auto=format&fit=crop', link: '/solutions/', span: 'glass' },
  { title: 'Endpoint Solutions', desc: 'Empowering your workforce with secure and efficient computing devices.', btn: 'View Offerings', img: 'https://images.unsplash.com/photo-1573164713988-8665fc963095?q=80&w=800&auto=format&fit=crop', link: '/solutions/', span: 'span-2x1' }
];

const defaultServices = [
  { num: '01', title: 'IT integration', desc: 'At every stage in the lifecycle of your IT Infrastructures, we ensure smooth, efficient, and robust technology alignment.', link: '/services/' },
  { num: '02', title: 'IT support', desc: 'Reactive and proactive support services to guarantee maximum uptime, security, and continuous business operations.', link: '/services/' },
  { num: '03', title: 'Managed services', desc: 'Offload your daily IT operations to our certified experts, allowing your team to focus strictly on innovation and growth.', link: '/services/' }
];

const defaultFigures = [
  { num: 34, label: 'years<br/>in business', prefix: '+' },
  { num: 330, label: 'employees', prefix: '' },
  { num: 700, label: 'MMAD Revenue', prefix: '' },
  { num: 18, label: 'Support<br/>centers', prefix: '' }
];

const offeringsThrough = ['Best of breed technologies', 'Skillful integrations', 'Sound architectures', 'Automation and orchestration', 'Qualified end-to-end services'];
const offeringsAchieve = ['Efficient use of converging technologies', 'Control over complexity', 'Simplified IT Operations', 'Agile infrastructure resources', 'Optimal performance and true resilience', 'Deep and broad support capabilities'];

const marqueeRow1 = ['Attijariwafa bank', 'Autoroutes du Maroc', 'Bank Al-Maghrib', 'BANK OF AFRICA', 'BANQUE POPULAIRE'];
const marqueeRow2 = ['CDG', 'EURAFRIC', 'Maroc Telecom', 'inwi', 'LafargeHolcim', 'MARBIO', 'OCP', 'ONCF'];

// ── COUNTER HOOK ──────────────────────────────────────
function useCounter(target, duration = 2000) {
  const [count, setCount] = useState(0);
  const ref = useRef(null);
  const isInView = useInView(ref, { once: true, margin: "-50px" });

  useEffect(() => {
    if (isInView) {
      const controls = animate(0, target, {
        duration: duration / 1000,
        ease: "easeOut",
        onUpdate(value) {
          setCount(Math.floor(value));
        }
      });
      return () => controls.stop();
    }
  }, [isInView, target, duration]);

  return [count, ref];
}

function FigureItem({ idx, defaultNum, defaultLabel, defaultPrefix }) {
  const { dbData, isEditMode, isPreviewMode } = useCMS();
  const numRaw = dbData?.homepage?.figures?.[idx]?.num || defaultNum;
  const num = parseInt(numRaw) || 0;
  const [count, ref] = useCounter(num);
  return (
    <div className="fig-item" ref={ref}>
      <h3>
        <InlineEditor as="span" dbPath={['homepage', 'figures', idx, 'prefix']} fallback={defaultPrefix || ''} />
        <span>
          {(isEditMode && !isPreviewMode) ? (
            <InlineEditor as="span" dbPath={['homepage', 'figures', idx, 'num']} fallback={defaultNum.toString()} />
          ) : (
            count
          )}
        </span>
      </h3>
      <InlineEditor as="p" dbPath={['homepage', 'figures', idx, 'label']} fallback={defaultLabel} />
    </div>
  );
}

// ── MAIN COMPONENT ────────────────────────────────────
export default function Home() {
  const { isEditMode, dbData } = useCMS();
  const homepageData = dbData?.homepage || {};

  const bentoSolutions = homepageData.bentoSolutions?.length ? homepageData.bentoSolutions.map((s, i) => ({
    ...defaultBentoSolutions[i] || {},
    ...s,
  })) : defaultBentoSolutions;

  const slides = homepageData.slides?.length
    ? homepageData.slides.map((s, i) => ({
        ...defaultSlides[i] || defaultSlides[0],
        ...s,
        img: homepageData.images?.[`slide${i + 1}`] || defaultSlides[i]?.img,
        meta: s.meta || defaultSlides[i]?.meta,
        desc: s.desc || defaultSlides[i]?.desc,
        btnText: s.btnText || s.cta || defaultSlides[i]?.btnText,
        btnLink: s.btnLink || s.ctaHref || defaultSlides[i]?.btnLink,
      }))
    : defaultSlides;

  const [si, setSi] = useState(0);
  const [isAnimating, setIsAnimating] = useState(false);
  const progressRef = useRef(null);
  const timerRef = useRef(null);
  const panelRef = useRef(null);

  const changeSlide = useCallback((newIndex) => {
    if (isAnimating || newIndex === si) return;
    setIsAnimating(true);
    setSi(newIndex);
    if (panelRef.current) {
      if (newIndex % 2 !== 0) panelRef.current.classList.add('is-right');
      else panelRef.current.classList.remove('is-right');
    }
    if (progressRef.current) {
      progressRef.current.style.transition = 'none';
      progressRef.current.style.width = '0%';
      setTimeout(() => {
        if (progressRef.current) {
          progressRef.current.style.transition = 'width 6000ms linear';
          progressRef.current.style.width = '100%';
        }
        setIsAnimating(false);
      }, 1400);
    } else {
      setTimeout(() => setIsAnimating(false), 1400);
    }
  }, [isAnimating, si]);

  const next = useCallback(() => changeSlide((si + 1) % slides.length), [si, slides.length, changeSlide]);
  const prev = useCallback(() => changeSlide((si - 1 + slides.length) % slides.length), [si, slides.length, changeSlide]);

  const resetTimer = useCallback(() => {
    if (timerRef.current) clearInterval(timerRef.current);
    timerRef.current = setInterval(next, 6000);
  }, [next]);

  useEffect(() => {
    if (isEditMode) return;
    if (progressRef.current) {
      setTimeout(() => {
        if (progressRef.current) {
          progressRef.current.style.transition = 'width 6000ms linear';
          progressRef.current.style.width = '100%';
        }
      }, 50);
    }
    resetTimer();
    return () => clearInterval(timerRef.current);
  }, [resetTimer, isEditMode]);

  const handlePrev = () => { if (timerRef.current) clearInterval(timerRef.current); prev(); resetTimer(); };
  const handleNext = () => { if (timerRef.current) clearInterval(timerRef.current); next(); resetTimer(); };

  const handleBtnClick = (e, link) => {
    if (link === '#solutions-section') {
      e.preventDefault();
      const el = document.querySelector('#solutions-section');
      if (el) el.scrollIntoView({ behavior: 'smooth' });
    }
  };

  const services = homepageData.services?.length ? homepageData.services.map((s, i) => ({
    ...defaultServices[i] || {},
    ...s,
    num: `0${i + 1}`
  })) : defaultServices;

  return (
    <div id="munisys-wrapper">
      {/* ── UNIFIED BACKGROUND FOR HERO & SOLUTIONS ── */}
      <div style={{ position: 'relative', width: '100%', background: 'var(--bg-gradient, linear-gradient(to bottom, #f4f7fb 0%, #ffffff 100%))' }}>
        
        {/* Absolute Background Container spanning full height of Hero + Solutions */}
        <div style={{ position: 'absolute', top: 0, bottom: 0, left: 0, right: 0, zIndex: 1, pointerEvents: 'none' }}>
          {/* Sticky Layer to keep backgrounds in viewport while scrolling */}
          <div style={{ position: 'sticky', top: 0, width: '100%', height: '100vh', overflow: 'hidden' }}>
            
            {/* Images */}
            <div className="images-wrapper" style={{ position: 'absolute', inset: 0, opacity: 0.08, mixBlendMode: 'multiply' }}>
              {slides.map((slide, i) => (
                <InlineImageEditor
                  key={i}
                  dbPath={['homepage', 'images', `slide${i + 1}`]}
                  fallback={defaultSlides[i]?.img || ''}
                  style={{ position: 'absolute', inset: 0, width: '100%', height: '100%' }}
                >
                  {(src) => (
                    <img src={src} className={`bg-image${i === si ? ' active' : ''}`} alt={slide.meta} />
                  )}
                </InlineImageEditor>
              ))}
            </div>

            {/* 3D Sphere */}
            <div style={{ position: 'absolute', inset: 0 }}>
              <DataCore isTextRight={si % 2 !== 0} />
            </div>

          </div>
        </div>

        {/* ── CONTENT ── */}
        <div style={{ position: 'relative', zIndex: 10 }}>
          {/* ── HERO ── */}
          <div className="hero-container" style={{ background: 'transparent' }}>

        <div className="text-panel" id="main-panel" ref={panelRef} style={{ background: 'transparent', border: 'none', boxShadow: 'none', paddingLeft: 0, paddingRight: 0 }}>
          <div className="text-slides-wrapper">
            {slides.map((slide, i) => (
              <div key={i} className={`text-slide${i === si ? ' active' : ''}`}>
                <InlineEditor as="div" className="meta" dbPath={['homepage', 'slides', i, 'meta']} fallback={slide.meta} />
                <InlineEditor as="h1" className="title" dbPath={['homepage', 'slides', i, 'h1']} fallback={slide.h1 || slide.title} />
                <InlineEditor as="p" className="desc" dbPath={['homepage', 'slides', i, 'desc']} fallback={slide.desc} />
                <div className="btn-wrap">
                  <Link
                    href={slide.btnLink || '#solutions-section'}
                    className="btn-elegant"
                    onClick={(e) => handleBtnClick(e, slide.btnLink)}
                  >
                    <InlineEditor as="span" dbPath={['homepage', 'slides', i, 'btnText']} fallback={slide.btnText || 'Our solutions'} />
                    <ArrowIcon />
                  </Link>
                </div>
              </div>
            ))}
          </div>

          <div className="controls-wrapper">
            <div className="slide-numbers">
              <span className="current">0{si + 1}</span>
              <span className="separator">/</span>
              <span className="total">0{slides.length}</span>
            </div>
            <div className="nav-buttons">
              <button className="nav-btn" id="btn-prev" onClick={handlePrev}>
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
                  <path d="M15 18l-6-6 6-6" />
                </svg>
              </button>
              <button className="nav-btn" id="btn-next" onClick={handleNext}>
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
                  <path d="M9 18l6-6-6-6" />
                </svg>
              </button>
            </div>
          </div>

          <div className="global-progress">
            <div className="fill" ref={progressRef}></div>
          </div>
        </div>
      </div>

      {/* ── 1. SOLUTIONS (BENTO GRID) ── */}
      <FadeIn delay={0.2}>
        <div className="sol-wrapper" id="solutions-section" style={{ background: 'transparent', boxShadow: 'none' }}>
          <section className="sol-sec" style={{ background: 'transparent' }}>
            <InlineEditor as="h2" className="sol-title" dbPath={['homepage', 'sections', 'solutionsTitle']} fallback="Our Solutions" style={{ color: '#1d4e79' }} />
            <div className="bento-grid">
              {bentoSolutions.map((sol, i) => (
                <Link
                  key={i}
                  href={sol.link}
                  className={`bento-card${sol.span === 'glass' ? ' glass' : ''}`}
                  style={{
                    position: 'relative',
                    overflow: 'hidden',
                    display: 'flex',
                    flexDirection: 'column',
                    ...(sol.span === 'span-2x3' ? { gridColumn: 'span 2', gridRow: 'span 3' }
                      : sol.span === 'span-2x1' ? { gridColumn: 'span 2', gridRow: 'span 1' }
                      : {})
                  }}
                >
                  <InlineImageEditor
                    dbPath={['homepage', 'solutions', i, 'img']}
                    fallback={sol.img || ''}
                    className="bento-bg-editor"
                    style={{ position: 'relative', width: '100%', height: '280px', objectFit: 'cover', flexShrink: 0 }}
                  />
                  <div className="bento-overlay"></div>
                  <div className="bento-content">
                    <InlineEditor as="h3" dbPath={['homepage', 'solutions', i, 'title']} fallback={sol.title} />
                    <InlineEditor as="p" dbPath={['homepage', 'solutions', i, 'desc']} fallback={sol.desc} />
                    {sol.btn && (
                      <span className="bento-link">
                        <InlineEditor as="span" dbPath={['homepage', 'solutions', i, 'btn']} fallback={sol.btn} />
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
                          <path d="M5 12h14M12 5l7 7-7 7" />
                        </svg>
                      </span>
                    )}
                  </div>
                </Link>
              ))}
            </div>
          </section>
        </div>
      </FadeIn>
      </div> {/* END zIndex 10 CONTENT */}
      </div> {/* END UNIFIED WRAPPER */}

      {/* ── 2. OFFERINGS ── */}
      <FadeIn>
        <section className="off-sec">
          <div className="off-header">
            <InlineEditor as="h2" className="off-title" dbPath={['homepage', 'sections', 'offeringsTitle']} fallback="Offerings" />
            <InlineEditor as="p" className="off-desc" dbPath={['homepage', 'sections', 'offeringsDesc']} fallback="An end-to-end &amp; coherent offer of solutions &amp; services for secure digital infrastructures" />
          </div>
          <div className="off-flow">
            <InlineEditor as="div" className="off-label-vert off-label-through" dbPath={['homepage', 'offeringsThroughTitle']} fallback="Through" />
            <div className="off-col">
            <DraggableList items={homepageData.offeringsListThrough || offeringsThrough} dbPath={['homepage', 'offeringsListThrough']} fallback={offeringsThrough} />
            </div>
            <InlineEditor as="div" className="off-label-vert off-label-achieve" dbPath={['homepage', 'offeringsAchieveTitle']} fallback="We achieve" />
            <div className="off-col">
            <DraggableList items={homepageData.offeringsListAchieve || offeringsAchieve} dbPath={['homepage', 'offeringsListAchieve']} fallback={offeringsAchieve} />
            </div>
          </div>
        </section>
      </FadeIn>

      <FadeIn>
        <div className="srv-wrapper">
          <section className="srv-sec" id="services">
            <div className="srv-left">
              <InlineEditor as="h2" dbPath={['homepage', 'sections', 'servicesTitle']} fallback="Our Services" />
            </div>
            <div className="srv-right">
              <DraggableServicesList services={services} />
            </div>
          </section>
        </div>
      </FadeIn>

      {/* ── 3.5 SOME FIGURES ── */}
      <FadeIn>
        <section className="figures-sec" id="about-us">
          <div className="fig-vertical">
            <InlineEditor as="span" dbPath={['homepage', 'sections', 'figuresTitle']} fallback={'SOME<br />FIGURES'} />
          </div>
          <div className="fig-grid">
            {defaultFigures.map((fig, i) => (
              <FigureItem key={i} idx={i} defaultNum={fig.num} defaultLabel={fig.label} defaultPrefix={fig.prefix} />
            ))}
          </div>
        </section>
      </FadeIn>

      {/* ── 4. INSIGHTS ── */}
      <FadeIn>
        <section className="ins-sec">
          <InlineEditor as="div" className="ins-title" dbPath={['homepage', 'sections', 'insightsTitle']} fallback="Insights" />
          <div className="ins-grid">
            <Link href="/insights" className="ins-card" style={{ textDecoration: 'none' }}>
              <div className="ins-img-wrap contain" style={{ background: '#f8f9fa', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                <InlineEditor as="span" dbPath={['homepage', 'sections', 'insightsCard1Title']} fallback="MUNISYS" style={{ fontSize: '2.5rem', fontWeight: 800, color: '#1d4e79', letterSpacing: '2px' }} />
              </div>
              <InlineEditor as="div" className="ins-text" dbPath={['homepage', 'sections', 'insightsCard1']} fallback="A new visual identity for Munisys" />
            </Link>
            <Link href="/insights" className="ins-card" style={{ textDecoration: 'none' }}>
              <InlineImageEditor dbPath={['homepage', 'insights', 1, 'img']} fallback="https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=800&auto=format&fit=crop">
                {(src) => (
                  <div className="ins-img-wrap">
                    <img src={src} alt="New headquarters" />
                  </div>
                )}
              </InlineImageEditor>
              <InlineEditor as="div" className="ins-text" dbPath={['homepage', 'sections', 'insightsCard2']} fallback="New headquarters" />
            </Link>
            <Link href="/insights" className="ins-card" style={{ textDecoration: 'none' }}>
              <div className="ins-img-wrap contain" style={{ flexDirection: 'column', gap: '1rem', textAlign: 'center' }}>
                <h2 style={{ fontSize: 'clamp(1.2rem,2vw,1.8rem)', fontWeight: 300, color: '#111', margin: 0, lineHeight: 1.2 }}>
                  <InlineEditor as="span" dbPath={['homepage', 'sections', 'insightsCard3Top']} fallback="HELIOS" /> <br />
                  <InlineEditor as="span" dbPath={['homepage', 'sections', 'insightsCard3Mid']} fallback="Investment Partners" style={{ color: '#f97316' }} />
                </h2>
                <InlineEditor as="h1" dbPath={['homepage', 'sections', 'insightsCard3Bot']} fallback="FIPAR" style={{ fontSize: 'clamp(1.8rem,3vw,2.8rem)', fontWeight: 900, color: '#1d4e79', margin: 0 }} />
              </div>
              <InlineEditor as="div" className="ins-text" style={{ textTransform: 'uppercase' }} dbPath={['homepage', 'sections', 'insightsCard3']} fallback="PRESS RELEASE" />
            </Link>
            <Link href="/insights" className="ins-card ins-card-more">
              <div className="ins-more-circle">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
                  <path d="M5 12h14M12 5l7 7-7 7" />
                </svg>
              </div>
              <InlineEditor as="div" className="ins-more-text" dbPath={['homepage', 'sections', 'insightsCardMore']} fallback="More" />
            </Link>
          </div>
        </section>
      </FadeIn>

      {/* ── 4.5 TECHNOLOGY PARTNERS (Glassmorphism) ── */}
      <FadeIn>
        <section className="partners-sec" id="technology-partners">
          <div className="part-content">
            <InlineEditor as="h2" dbPath={['homepage', 'sections', 'partnersTitle']} fallback="Technology partners" />
            <InlineEditor as="p" dbPath={['homepage', 'sections', 'partnersDesc']} fallback="The IT infrastructures we design and deploy for our clients are at the heart of their business and determine their capacity to innovate and stand out. We build solid ties with our technology partners and acquire in-house expertise to ensure true resilience and agility." />
            <Link href="/technology-partners" className="part-link">
              <InlineEditor as="span" dbPath={['homepage', 'sections', 'partnersLinkText']} fallback="Read more" /> <ArrowIcon />
            </Link>
          </div>
        </section>
      </FadeIn>

      {/* ── 5. CUSTOMER REFERENCES (Infinite Marquee) ── */}
      <FadeIn>
        <section className="ref-sec" id="customer-references" style={{ paddingTop: '4rem' }}>
          {/* Marquee 1 - Left to Right */}
          <div style={{ overflow: 'hidden', marginBottom: '3rem', position: 'relative' }}>
            <div className="marquee-wrapper">
              {[...marqueeRow1, ...marqueeRow1].map((name, i) => (
                <div className="marquee-grid" key={i} style={{ display: 'flex', gap: '4rem', padding: '0 2rem', flexShrink: 0 }}>
                  <div className="ref-logo" style={{ lineHeight: "1.5", padding: "1rem 0" }}>{name}</div>
                </div>
              ))}
            </div>
          </div>

          {/* Marquee 2 - Right to Left */}
          <div style={{ overflow: 'hidden', position: 'relative' }}>
            <div className="marquee-wrapper" style={{ animationDirection: 'reverse', animationDuration: '40s' }}>
              {[...marqueeRow2, ...marqueeRow2].map((name, i) => (
                <div className="marquee-grid" key={i} style={{ display: 'flex', gap: '4rem', padding: '0 2rem', flexShrink: 0 }}>
                  <div className="ref-logo" style={{ lineHeight: "1.5", padding: "1rem 0" }}>{name}</div>
                </div>
              ))}
            </div>
          </div>

          <div style={{ display: 'flex', justifyContent: 'center', width: '100%', paddingTop: '4rem', paddingBottom: '3rem' }}>
            <Link
              href="/customer-references"
              style={{ margin: '0 auto', display: 'inline-block', fontWeight: 800, color: 'var(--bordeaux)', textTransform: 'uppercase', textDecoration: 'none', borderBottom: '2px solid var(--bordeaux)', paddingBottom: '6px', fontSize: '1rem', letterSpacing: '1px' }}
            >
              <InlineEditor as="span" dbPath={['homepage', 'sections', 'referencesLinkText']} fallback="VOIR TOUTES LES RÉFÉRENCES" />
            </Link>
          </div>
        </section>
      </FadeIn>


    </div> // END MUNISYS WRAPPER
  );
}
