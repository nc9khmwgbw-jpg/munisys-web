"use client";

import { useEffect, useState } from "react";

export default function ReadingProgressBar() {
  const [progress, setProgress] = useState(0);
  const [visible, setVisible] = useState(false);

  useEffect(() => {
    const updateProgress = () => {
      const scrollTop = window.scrollY;
      const docHeight = document.documentElement.scrollHeight - window.innerHeight;
      const pct = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;
      setProgress(Math.min(pct, 100));
      setVisible(scrollTop > 80); // show after 80px scroll
    };

    window.addEventListener("scroll", updateProgress, { passive: true });
    return () => window.removeEventListener("scroll", updateProgress);
  }, []);

  return (
    <>
      {/* Fixed progress bar at very top of viewport */}
      <div
        style={{
          position: "fixed",
          top: 0,
          left: 0,
          right: 0,
          height: "3px",
          background: "rgba(146, 43, 85, 0.12)",
          zIndex: 9999999,
          opacity: visible ? 1 : 0,
          transition: "opacity 0.3s ease",
          pointerEvents: "none",
        }}
      >
        <div
          style={{
            height: "100%",
            width: `${progress}%`,
            background: "linear-gradient(90deg, var(--bordeaux, #922b55) 0%, #c05280 100%)",
            borderRadius: "0 2px 2px 0",
            transition: "width 0.1s linear",
            boxShadow: "0 0 8px rgba(146, 43, 85, 0.5)",
          }}
        />
      </div>

      {/* Reading percentage indicator bubble */}
      {visible && progress > 5 && progress < 98 && (
        <div
          style={{
            position: "fixed",
            bottom: "2rem",
            right: "1.5rem",
            width: "52px",
            height: "52px",
            borderRadius: "50%",
            background: "white",
            boxShadow: "0 4px 20px rgba(0,0,0,0.12), 0 1px 4px rgba(0,0,0,0.06)",
            display: "flex",
            flexDirection: "column",
            alignItems: "center",
            justifyContent: "center",
            zIndex: 99999,
            border: "2px solid rgba(146,43,85,0.15)",
            pointerEvents: "none",
          }}
        >
          {/* Circular progress ring */}
          <svg width="52" height="52" style={{ position: "absolute", top: -2, left: -2, transform: "rotate(-90deg)" }}>
            <circle
              cx="28"
              cy="28"
              r="22"
              fill="none"
              stroke="rgba(146,43,85,0.1)"
              strokeWidth="3"
            />
            <circle
              cx="28"
              cy="28"
              r="22"
              fill="none"
              stroke="var(--bordeaux, #922b55)"
              strokeWidth="3"
              strokeDasharray={`${2 * Math.PI * 22}`}
              strokeDashoffset={`${2 * Math.PI * 22 * (1 - progress / 100)}`}
              strokeLinecap="round"
              style={{ transition: "stroke-dashoffset 0.2s linear" }}
            />
          </svg>
          <span style={{ fontSize: "0.65rem", fontWeight: 800, color: "var(--bordeaux, #922b55)", lineHeight: 1, zIndex: 1 }}>
            {Math.round(progress)}%
          </span>
        </div>
      )}
    </>
  );
}
