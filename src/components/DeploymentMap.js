"use client";
import React, { useState, useEffect } from "react";
import { ComposableMap, Geographies, Geography, Marker, Line } from "react-simple-maps";

const geoUrl = "https://cdn.jsdelivr.net/npm/world-atlas@2/countries-110m.json";

const COLOR = {
  "Siège social": "#922b55",
  "Partenariats": "#1d4e79",
  "Projets Actifs": "#27ae60",
  "Expansion": "#f39c12"
};

const DEPS = [
  { id:"ma", country:"Maroc", cat:"Siège social", flag:"🇲🇦", projects:47, lat:31.7917, lon:-7.0926, desc:"Casablanca - Hub stratégique et centre d'excellence Munisys." },
  { id:"dz", country:"Algérie", cat:"Partenariats", flag:"🇩🇿", projects:7, lat:28.0339, lon:1.6596, desc:"Développement d'infrastructures télécoms avec partenaires locaux." },
  { id:"tn", country:"Tunisie", cat:"Partenariats", flag:"🇹🇳", projects:9, lat:33.8869, lon:9.5375, desc:"Projets cloud & cybersécurité pour le secteur financier." },
  { id:"sn", country:"Sénégal", cat:"Projets Actifs", flag:"🇸🇳", projects:8, lat:14.4974, lon:-14.4524, desc:"Dakar - Déploiement de solutions datacenter et réseaux." },
  { id:"ci", country:"Côte d'Ivoire", cat:"Projets Actifs", flag:"🇨🇮", projects:6, lat:7.54, lon:-5.5471, desc:"Abidjan - Modernisation IT pour les institutions publiques." },
  { id:"cm", country:"Cameroun", cat:"Projets Actifs", flag:"🇨🇲", projects:5, lat:3.8480, lon:11.5021, desc:"Intégration de systèmes sécurisés pour l'industrie." },
  { id:"mr", country:"Mauritanie", cat:"Projets Actifs", flag:"🇲🇷", projects:2, lat:21.0079, lon:-10.9408, desc:"Infrastructures réseaux critiques." },
  { id:"fr", country:"France", cat:"Partenariats", flag:"🇫🇷", projects:12, lat:46.2276, lon:2.2137, desc:"Paris - R&D et partenariats technologiques européens." },
  { id:"sa", country:"Arabie Saoudite", cat:"Expansion", flag:"🇸🇦", projects:4, lat:23.8859, lon:45.0792, desc:"Riyad - Nouveaux projets d'architecture IT globale." }
];

export default function DeploymentMap() {
  const [mounted, setMounted] = useState(false);
  const [active, setActive] = useState(null);
  const [hovered, setHovered] = useState(null);
  const [clicked, setClicked] = useState(null);

  useEffect(() => {
    setMounted(true);
  }, []);

  useEffect(() => {
    if (clicked) {
      setActive(clicked);
      return;
    }
    if (hovered) {
      setActive(hovered);
      return;
    }
    setActive(null);
  }, [hovered, clicked]);

  const hq = DEPS.find(d => d.id === "ma");

  return (
    <section className="map-sec" style={{
      width: "100%", position:"relative", overflow:"hidden",
      background:"transparent", color:"var(--text-dark, #1e293b)", padding:"8rem 0"
    }}>

      <div style={{ 
        width: "100%", maxWidth: "1250px", margin:"0 auto", position:"relative", zIndex:2,
      }}>
        <div style={{ display: "grid", gridTemplateColumns: "350px minmax(0, 1fr)", gap: "2rem", alignItems: "stretch" }}>

          {/* ── LEFT COLUMN: STATS & COUNTRIES ── */}
          <div style={{ display: "flex", flexDirection: "column", gap: "2rem" }}>
              
              {/* TOP WIDGET: STATS CARD */}
              <div style={{
                background: "#ffffff", borderRadius: "32px", padding: "2.5rem", flexShrink: 0,
                boxShadow: "0 20px 40px -10px rgba(0,0,0,0.05), 0 1px 3px rgba(0,0,0,0.05), inset 0 0 0 1px rgba(0,0,0,0.03)",
                display: "flex", flexDirection: "column", border: "1px solid rgba(0,0,0,0.05)"
              }}>
                <div style={{ marginBottom: "2rem" }}>
                  <span style={{ display:"inline-block",fontSize:"0.75rem",fontWeight:800,letterSpacing:"3px",textTransform:"uppercase",color:"var(--bordeaux,#922b55)",background:"rgba(146,43,85,0.1)",padding:"8px 16px",borderRadius:"30px",marginBottom:"1.2rem" }}>
                    Présence internationale
                  </span>
                  <h2 style={{ fontSize:"2.2rem",fontWeight:800,color:"var(--blue-dark,#1d4e79)",lineHeight:1.1,margin:"0 0 1rem", letterSpacing:"-1px" }}>
                    Déploiement dans <br/><span style={{ color:"var(--bordeaux,#922b55)" }}>9 pays</span>
                  </h2>
                  <p style={{ color:"var(--text-gray, #64748b)",fontSize:"0.95rem",margin:"0",lineHeight:1.5 }}>
                    De Casablanca à Riyad, nos experts déploient des solutions IT de pointe.
                  </p>
                </div>
                <div style={{ display:"grid", gridTemplateColumns:"1fr 1fr", gap:"1.5rem 1rem" }}>
                  {Object.entries({
                    "Pays couverts": "9",
                    "Projets IT": "98+",
                    "Expertise (ans)": "12+",
                    "Experts certifiés": "80+"
                  }).map(([l,v]) => (
                    <div key={l} style={{ display:"flex", flexDirection:"column" }}>
                      <div style={{ fontSize:"2.5rem",fontWeight:900,backgroundImage:"linear-gradient(135deg, #922b55 0%, #ff4d4d 100%)",WebkitBackgroundClip:"text",WebkitTextFillColor:"transparent",lineHeight:1, letterSpacing:"-1px", marginBottom:"4px" }}>{v}</div>
                      <div style={{ fontSize:"0.65rem",fontWeight:800,color:"#475569",textTransform:"uppercase",letterSpacing:"1px" }}>{l}</div>
                    </div>
                  ))}
                </div>
              </div>

              {/* BOTTOM WIDGET: COUNTRIES CARD */}
              <div style={{
                background: "#ffffff", borderRadius: "32px", padding: "1.5rem 1.5rem 2rem 1.5rem", flex: 1, minHeight: 0,
                boxShadow: "0 20px 40px -10px rgba(0,0,0,0.05), 0 1px 3px rgba(0,0,0,0.05), inset 0 0 0 1px rgba(0,0,0,0.03)",
                display: "flex", flexDirection: "column", border: "1px solid rgba(0,0,0,0.05)"
              }}>
                <h3 style={{ fontSize:"0.75rem",fontWeight:800,color:"#475569",textTransform:"uppercase",letterSpacing:"2px", margin:"0 0 1rem 0.5rem", flexShrink: 0 }}>Pays Déployés</h3>
                <div style={{ overflowY: "auto", display: "flex", flexDirection: "column", gap: "0.6rem", paddingRight: "0.5rem", flex: 1 }}>
                  {DEPS.map(dep => {
                    const color = COLOR[dep.cat];
                    const isAct = active?.id === dep.id;
                    return (
                      <button key={dep.id}
                        onClick={() => setClicked(prev => prev?.id === dep.id ? null : dep)}
                        onMouseEnter={() => setHovered(dep)}
                        onMouseLeave={() => setHovered(null)}
                        style={{
                          display:"flex",alignItems:"center",gap:"16px",
                          background: isAct ? `${color}15` : "rgba(255,255,255,0.5)",
                          border:`1px solid ${isAct ? color+"55" : "rgba(255,255,255,0.8)"}`,
                          borderRadius:"16px",padding:"12px 18px",cursor:"pointer",
                          textAlign:"left",transition:"all 0.2s cubic-bezier(0.4, 0, 0.2, 1)",
                          boxShadow: isAct ? `0 4px 12px ${color}22` : "0 2px 8px rgba(0,0,0,0.02)",
                          transform: isAct ? "translateX(5px)" : "none", flexShrink: 0
                        }}>
                        <span style={{ fontSize:"1.4rem", filter:"drop-shadow(0 2px 4px rgba(0,0,0,0.1))" }}>{dep.flag}</span>
                        <div style={{ flex:1, minWidth:0, display: "flex", flexDirection: "column" }}>
                          <span style={{ fontWeight:800,fontSize:"0.9rem",color:isAct ? color : "#1d4e79", whiteSpace:"nowrap", overflow:"hidden", textOverflow:"ellipsis" }}>{dep.country}</span>
                          <span style={{ fontSize:"0.6rem",fontWeight:700,color:"#64748b", textTransform:"uppercase" }}>{dep.cat}</span>
                        </div>
                        <div style={{ display: "flex", flexDirection: "column", alignItems: "flex-end" }}>
                          <span style={{ fontSize:"1rem",fontWeight:900,color }}>{dep.projects}</span>
                        </div>
                      </button>
                    );
                  })}
                </div>
              </div>
          </div>

          {/* ── RIGHT COLUMN: MAP CARD ── */}
          <div style={{ 
            minWidth: 0, background:"linear-gradient(135deg, #eef2f6 0%, #dbe4ef 100%)",
            borderRadius:"32px",boxShadow:"0 20px 40px -10px rgba(0,0,0,0.08), inset 0 2px 0 rgba(255,255,255,1)",
            border:"1px solid rgba(255,255,255,0.8)",overflow:"hidden", position:"relative", 
            display: "flex", flexDirection: "column", minHeight: "700px"
          }}>

            {/* ── MAP HEADER (MAC OS STYLE) ── */}
            <div style={{ padding:"20px 32px", display:"flex", flexWrap:"wrap", gap:"16px", justifyContent:"space-between", alignItems:"center", zIndex:10, background:"rgba(255,255,255,0.4)", backdropFilter:"blur(12px)", borderBottom:"1px solid rgba(255,255,255,0.6)" }}>
              <div style={{ display:"flex", alignItems:"center", gap:"16px" }}>
                <div style={{ display:"flex", gap:"8px" }}>
                  <div style={{width:"12px",height:"12px",borderRadius:"50%",background:"#ff5f56",boxShadow:"inset 0 0 0 1px rgba(0,0,0,0.1)"}}/>
                  <div style={{width:"12px",height:"12px",borderRadius:"50%",background:"#ffbd2e",boxShadow:"inset 0 0 0 1px rgba(0,0,0,0.1)"}}/>
                  <div style={{width:"12px",height:"12px",borderRadius:"50%",background:"#27c93f",boxShadow:"inset 0 0 0 1px rgba(0,0,0,0.1)"}}/>
                </div>
                <span style={{ fontSize:"0.8rem",fontWeight:800,color:"var(--blue-dark, #1d4e79)",textTransform:"uppercase",letterSpacing:"2px", lineHeight: 1.4, flexShrink:0, opacity:0.8 }}>
                  Visualisateur de déploiement
                </span>
              </div>
              <div style={{ display:"flex", gap:"24px", flexWrap:"wrap" }}>
                {Object.entries(COLOR).map(([cat,col]) => (
                  <div key={cat} style={{ display:"flex",alignItems:"center",gap:"10px",fontSize:"0.75rem",fontWeight:800,color:"#475569", lineHeight: 1.4, textTransform:"uppercase", letterSpacing:"1px" }}>
                    <div style={{ width:"10px",height:"10px",borderRadius:"50%",background:col, boxShadow:`0 0 15px ${col}` }} />
                    {cat}
                  </div>
                ))}
              </div>
            </div>

            <div style={{ width: "100%", flex: 1, minHeight: "500px", position: "relative", overflow: "hidden" }}>
              
              {/* ── ACTIVE CARD OVERLAY (COMPACT WIDGET) ── */}
              {active && (
                <div style={{
                  position: "absolute",
                  bottom: "2rem",
                  left: "50%",
                  transform: "translateX(-50%)",
                  width: "350px",
                  zIndex: 100,
                  background: "rgba(255, 255, 255, 0.85)",
                  backdropFilter: "blur(12px)",
                  borderRadius:"16px", 
                  padding:"20px",
                  boxShadow: "0 8px 32px rgba(0,0,0,0.12)",
                  border: `1px solid ${COLOR[active.cat]}44`,
                  borderTop: `4px solid ${COLOR[active.cat]}`,
                  display:"flex", flexDirection:"column", boxSizing:"border-box",
                  pointerEvents: "none",
                  animation: "fade-in 0.3s ease-out"
                }}>
                  <div style={{ display:"flex",alignItems:"center",gap:"16px",marginBottom:"16px" }}>
                    <span style={{ fontSize:"2rem", filter:"drop-shadow(0 2px 4px rgba(0,0,0,0.1))", flexShrink: 0 }}>{active.flag}</span>
                    <div style={{ flex:1, minWidth:0 }}>
                      <div style={{ fontWeight:800,fontSize:"1.3rem",color:"#1d4e79", whiteSpace:"nowrap", overflow:"hidden", textOverflow:"ellipsis", lineHeight:1.2 }}>{active.country}</div>
                      <span style={{ fontSize:"0.65rem",fontWeight:800,color:COLOR[active.cat],background:`${COLOR[active.cat]}15`,padding:"4px 10px",borderRadius:"12px",display:"inline-block",marginTop:"6px", textTransform:"uppercase", letterSpacing:"0.5px" }}>{active.cat}</span>
                    </div>
                  </div>
                  
                  <div style={{ display:"flex", alignItems:"baseline", gap:"8px", marginBottom:"12px", paddingBottom:"12px", borderBottom:"1px solid rgba(0,0,0,0.05)" }}>
                      <div style={{ fontSize:"2.2rem",fontWeight:900,color:"var(--bordeaux,#922b55)",lineHeight:1 }}>{active.projects}</div>
                      <div style={{ fontSize:"0.65rem",color:"#64748b",fontWeight:800, textTransform:"uppercase", letterSpacing:"1px", lineHeight:1.2 }}>projets<br/>déployés</div>
                  </div>
                  
                  <p style={{ fontSize:"0.9rem",color:"#475569",lineHeight:1.5,margin:0, fontWeight:500 }}>{active.desc}</p>
                  
                  {clicked && (
                    <button style={{ pointerEvents: "auto", alignSelf: "stretch", textAlign:"center", marginTop:"16px",fontSize:"0.75rem",fontWeight:700,color:"#64748b",background:"#f1f5f9",border:"none",borderRadius:"8px",padding:"8px",cursor:"pointer", transition:"all 0.2s" }} onMouseOver={e=>e.currentTarget.style.background="#e2e8f0"} onMouseOut={e=>e.currentTarget.style.background="#f1f5f9"} onClick={() => setClicked(null)}>
                      ✕ Fermer
                    </button>
                  )}
                </div>
              )}

              {mounted && (
                <ComposableMap
                  projection="geoMercator"
                  projectionConfig={{
                    scale: 550,
                    center: [15, 20] 
                  }}
                  style={{ width: "100%", height: "100%" }}
                >
                <defs>
                  <filter id="shadow">
                    <feDropShadow dx="0" dy="2" stdDeviation="3" floodColor="#000000" floodOpacity="0.15" />
                  </filter>
                  <filter id="map-shadow">
                    <feDropShadow dx="0" dy="8" stdDeviation="8" floodColor="#1d4e79" floodOpacity="0.15" />
                  </filter>
                </defs>

                {/* Connection lines to HQ (Morocco) */}
                {hq && DEPS.filter(d => d.id !== "ma" && d.lon !== null).map(d => (
                  <Line
                    key={`line-${d.id}`}
                    from={[hq.lon, hq.lat]}
                    to={[d.lon, d.lat]}
                    stroke={COLOR[d.cat]}
                    strokeWidth={1}
                    strokeLinecap="round"
                    strokeDasharray="4 4"
                    style={{
                      opacity: active ? (active.id === d.id || active.id === "ma" ? 0.8 : 0.1) : 0.4,
                      transition: "opacity 0.3s ease"
                    }}
                  />
                ))}

                <Geographies geography={geoUrl}>
                  {({ geographies }) =>
                    geographies.map((geo) => {
                      const isDeployed = DEPS.some(d => geo.properties.name.includes(d.country) || (d.country==="Côte d'Ivoire" && geo.properties.name==="Côte d'Ivoire"));
                      return (
                        <Geography
                          key={geo.rsmKey}
                          geography={geo}
                          fill={isDeployed ? "#ffffff" : "#e2e8f0"}
                          stroke={isDeployed ? "#cbd5e1" : "#ffffff"}
                          strokeWidth={isDeployed ? 1 : 0.8}
                          filter={isDeployed ? "url(#map-shadow)" : "none"}
                          style={{
                            default: { outline: "none", transition: "all 0.3s" },
                            hover:   { outline: "none", fill: isDeployed ? "#f8fafc" : "#cbd5e1", cursor: isDeployed ? "pointer" : "default" },
                            pressed: { outline: "none", fill: isDeployed ? "#f1f5f9" : "#94a3b8" }
                          }}
                        />
                      );
                    })
                  }
                </Geographies>

                {/* Markers */}
                {DEPS.filter(d => d.lon !== null).map(d => {
                  const color = COLOR[d.cat];
                  const isAct = active?.id === d.id;
                  const isHQ = d.id === "ma";
                  return (
                    <Marker 
                      key={d.id} 
                      coordinates={[d.lon, d.lat]}
                      onMouseEnter={() => setHovered(d)}
                      onMouseLeave={() => setHovered(null)}
                      onClick={() => setClicked(prev => prev?.id === d.id ? null : d)}
                      style={{ cursor: "pointer" }}
                    >
                      {(isAct || isHQ) && (
                        <circle r={isHQ ? 24 : 16} fill={color} opacity={0.15} style={{ animation: "pulse-marker 2s infinite ease-in-out" }} />
                      )}
                      <circle r={isHQ ? 8 : 6} fill="#ffffff" filter="url(#shadow)" />
                      <circle r={isHQ ? 6 : 4} fill={color} style={{ transition: "all 0.2s ease", transform: isAct ? "scale(1.4)" : "scale(1)" }} />
                      {isHQ && <text textAnchor="middle" y={2} fill="#ffffff" fontSize={6} fontWeight="bold" style={{ pointerEvents: "none" }}>★</text>}
                    </Marker>
                  );
                })}
                </ComposableMap>
              )}
            </div>
          </div>
        </div>
      </div>
      
      <style dangerouslySetInnerHTML={{ __html: `
        @keyframes pulse-marker {
          0% { transform: scale(1); opacity: 0.25; }
          50% { transform: scale(1.5); opacity: 0; }
          100% { transform: scale(1); opacity: 0; }
        }
        @keyframes fade-in-up {
          from { opacity: 0; transform: translateY(10px); }
          to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fade-in {
          from { opacity: 0; }
          to { opacity: 1; }
        }
      `}} />
    </section>
  );
}
