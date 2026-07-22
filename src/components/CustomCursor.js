"use client";

import { useEffect, useState } from "react";
import { motion, useMotionValue, useSpring } from "framer-motion";
import { usePathname } from "next/navigation";
import { useCMS } from "./CMSProvider";

export default function CustomCursor() {
  const [isMobile, setIsMobile] = useState(true);
  const [isHovered, setIsHovered] = useState(false);
  const pathname = usePathname();
  const { isEditMode } = useCMS();

  const cursorX = useMotionValue(-100);
  const cursorY = useMotionValue(-100);

  // Smooth out the movement with a spring
  const springConfig = { damping: 25, stiffness: 300, mass: 0.5 };
  const cursorXSpring = useSpring(cursorX, springConfig);
  const cursorYSpring = useSpring(cursorY, springConfig);

  useEffect(() => {
    // Only enable on desktop screens
    if (window.innerWidth >= 768) {
      setIsMobile(false);
    }
    
    const handleResize = () => {
      setIsMobile(window.innerWidth < 768);
    };
    window.addEventListener('resize', handleResize);

    const moveCursor = (e) => {
      cursorX.set(e.clientX);
      cursorY.set(e.clientY);
    };

    const handleMouseOver = (e) => {
      if (
        e.target.tagName?.toLowerCase() === "a" ||
        e.target.tagName?.toLowerCase() === "button" ||
        e.target.closest("a") ||
        e.target.closest("button") ||
        e.target.classList.contains("munisys-editable") ||
        e.target.closest(".munisys-editable")
      ) {
        setIsHovered(true);
      } else {
        setIsHovered(false);
      }
    };

    if (window.innerWidth >= 768) {
      window.addEventListener("mousemove", moveCursor);
      window.addEventListener("mouseover", handleMouseOver);
    }

    return () => {
      window.removeEventListener("resize", handleResize);
      window.removeEventListener("mousemove", moveCursor);
      window.removeEventListener("mouseover", handleMouseOver);
    };
  }, []);

  if (isMobile) return null;

  return (
    <>
      {/* Inner Dot */}
      <motion.div
        style={{
          position: "fixed",
          left: cursorX,
          top: cursorY,
          width: 8,
          height: 8,
          borderRadius: "50%",
          backgroundColor: "var(--bordeaux, #922b55)",
          boxShadow: "0 0 0 1px rgba(255, 255, 255, 0.6)",
          pointerEvents: "none",
          transform: "translate(-50%, -50%)",
          zIndex: 9999999,
        }}
      />
      {/* Outer Ring */}
      <motion.div
        animate={{
          scale: isHovered ? 2 : 1,
          opacity: isHovered ? 0.3 : 0.8,
        }}
        transition={{ duration: 0.2 }}
        style={{
          position: "fixed",
          left: cursorXSpring,
          top: cursorYSpring,
          width: 32,
          height: 32,
          borderRadius: "50%",
          border: "2px solid var(--bordeaux, #922b55)",
          boxShadow: "0 0 0 1px rgba(255, 255, 255, 0.4), inset 0 0 0 1px rgba(255, 255, 255, 0.4)",
          pointerEvents: "none",
          transform: "translate(-50%, -50%)",
          zIndex: 9999998,
        }}
      />
      <style dangerouslySetInnerHTML={{__html: `
        /* Hide default cursor on desktop when component mounts */
        @media (hover: hover) and (pointer: fine) {
          body *:not(#munisys-admin-sidebar):not(#munisys-admin-sidebar *):not(#munisys-admin-toggle):not(#munisys-admin-toggle *) {
            cursor: none !important;
          }
        }
      `}} />
    </>
  );
}
