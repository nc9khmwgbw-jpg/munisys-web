css = """
/* ── INNER PAGES HERO (WordPress style) ── */
.page-hero {
    background: var(--blue-dark);
    color: #fff;
    padding: 8rem 5rem 6rem 5rem;
    text-align: center;
    position: relative;
    overflow: hidden;
    width: 100%;
}

.page-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: radial-gradient(circle at right top, rgba(146, 43, 85, 0.2), transparent 50%);
}

.page-hero h1 {
    font-size: 3.5rem;
    font-weight: 800;
    margin-bottom: 1.5rem;
    position: relative;
    z-index: 2;
}

.page-hero p {
    font-size: 1.2rem;
    color: rgba(255, 255, 255, 0.8);
    max-width: 800px;
    margin: 0 auto;
    position: relative;
    z-index: 2;
    line-height: 1.6;
}

.page-content {
    max-width: 1200px;
    margin: 0 auto;
    padding: 5rem 2rem;
}

.section-title {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--blue-dark);
    margin-bottom: 1.5rem;
    text-align: center;
}

.section-desc {
    font-size: 1.1rem;
    color: var(--text-gray);
    line-height: 1.8;
    margin-bottom: 4rem;
    text-align: center;
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
}

/* ── CAREERS PAGE ── */
.job-list {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    max-width: 800px;
    margin: 0 auto;
}

.job-card {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #fff;
    border: 1px solid #e2e8f0;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
    transition: all 0.2s;
}

.job-card:hover {
    border-color: var(--bordeaux);
    box-shadow: 0 10px 15px rgba(0, 0, 0, 0.05);
}

.job-info h3 {
    font-size: 1.4rem;
    font-weight: 700;
    color: var(--blue-dark);
    margin-bottom: 0.5rem;
}

.job-info p {
    font-size: 0.95rem;
    color: var(--text-gray);
    font-weight: 500;
}

.job-btn {
    background: var(--bordeaux);
    color: #fff;
    padding: 0.8rem 1.5rem;
    border-radius: 4px;
    font-weight: 600;
    text-decoration: none;
    transition: background 0.2s;
}

.job-btn:hover {
    background: #7a2245;
}

/* ── TECHNOLOGY PARTNERS PAGE ── */
.part-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 1.5rem;
    justify-content: center;
}

.part-card {
    background: #f4f7f9;
    padding: 1.5rem 2.5rem;
    border-radius: 50px;
    font-weight: 800;
    font-size: 1.2rem;
    color: var(--bordeaux);
    border: 2px solid transparent;
    transition: all 0.2s;
    text-decoration: none;
}

.part-card:hover {
    background: #fff;
    border-color: var(--bordeaux);
    box-shadow: 0 10px 20px rgba(146, 43, 85, 0.1);
}

/* ── CUSTOMER REFERENCES PAGE ── */
.ref-cards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 2rem;
}

.ref-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    padding: 2rem;
    border-radius: 8px;
    text-align: center;
    font-weight: 700;
    font-size: 1.1rem;
    color: var(--blue-dark);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02);
    transition: transform 0.2s;
}

.ref-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 15px rgba(0, 0, 0, 0.05);
    border-color: var(--blue-light);
}

/* ── SERVICES PAGE ── */
.services-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3rem;
}

.service-card {
    background: #f4f7f9;
    padding: 3rem;
    border-radius: 8px;
    border-top: 4px solid var(--bordeaux);
}

.service-card h3 {
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--blue-dark);
    margin-bottom: 1.5rem;
}

.service-card p {
    font-size: 1.05rem;
    color: var(--text-gray);
    line-height: 1.8;
}

@media(max-width: 768px) {
    .services-grid {
        grid-template-columns: 1fr !important;
    }
    .job-card {
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 1.5rem !important;
    }
}
"""

with open("src/app/globals.css", "a") as f:
    f.write("\n" + css + "\n")
