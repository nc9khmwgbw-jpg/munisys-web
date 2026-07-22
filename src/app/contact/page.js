"use client";
import { useCMS } from '@/components/CMSProvider';
import InlineEditor from '@/components/InlineEditor';
import { useState } from 'react';

export default function ContactPage() {
    const { dbData } = useCMS();
    const data = dbData?.contact || {};
    const [sent, setSent] = useState(false);

    return (
        <div className="cnt-wrapper">
            <div className="cnt-orb cnt-orb-1"></div>
            <div className="cnt-orb cnt-orb-2"></div>
            <div className="cnt-orb cnt-orb-3"></div>

            <div className="cnt-header">
                <span className="cnt-eyebrow">
                    <InlineEditor as="span" dbPath={['contact', 'eyebrow']} fallback={data.eyebrow || 'Contact'} />
                </span>
                <h1 className="cnt-headline">
                    <InlineEditor as="span" dbPath={['contact', 'heroMainTitle']} fallback="Let's Build" /><br /><span><InlineEditor as="span" dbPath={['contact', 'heroMainTitleBold']} fallback="Together." /></span>
                </h1>
                <p className="cnt-subline">
                    <InlineEditor as="span" dbPath={['contact', 'subline']} fallback={data.subline || 'Ready to accelerate your digital transformation? Our experts are here, available, and eager to help you shape the future.'} />
                </p>
            </div>

            <div className="cnt-body">
                <div className="cnt-info-panel">
                    <div className="cnt-info-card">
                        <div className="cnt-icon-wrap">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z" />
                                <circle cx="12" cy="10" r="3" />
                            </svg>
                        </div>
                        <div className="cnt-info-text">
                            <h4><InlineEditor as="span" dbPath={['contact', 'hqTitle']} fallback={data.hqTitle || 'Headquarters'} /></h4>
                            <p><InlineEditor as="span" dbPath={['contact', 'hqText']} fallback={data.hqText || '123 Innovation Drive, Tech District, Casablanca'} /></p>
                        </div>
                    </div>
                    <div className="cnt-info-card">
                        <div className="cnt-icon-wrap">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth="2">
                                <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div className="cnt-info-text">
                            <h4><InlineEditor as="span" dbPath={['contact', 'emailTitle']} fallback={data.emailTitle || 'Email Us'} /></h4>
                            <p><InlineEditor as="span" dbPath={['contact', 'emailText']} fallback={data.emailText || 'contact@munisys.com'} /></p>
                        </div>
                    </div>
                    <div className="cnt-info-card">
                        <div className="cnt-icon-wrap">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth="2">
                                <path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <div className="cnt-info-text">
                            <h4><InlineEditor as="span" dbPath={['contact', 'callTitle']} fallback={data.callTitle || 'Call Us'} /></h4>
                            <p><InlineEditor as="span" dbPath={['contact', 'callText']} fallback={data.callText || '+212 5 22 00 00 00'} /></p>
                        </div>
                    </div>

                    <div className="cnt-social-row">
                        <a href="#" className="cnt-social-btn" aria-label="LinkedIn">
                            <svg fill="currentColor" viewBox="0 0 24 24">
                                <path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z" />
                                <circle cx="4" cy="4" r="2" fill="currentColor" />
                            </svg>
                        </a>
                        <a href="#" className="cnt-social-btn" aria-label="Twitter">
                            <svg fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                            </svg>
                        </a>
                    </div>
                </div>

                <div className="cnt-form-glass">
                    <InlineEditor as="h2" className="cnt-form-title" dbPath={['contact', 'formTitle']} fallback="Send a Message" />
                    <InlineEditor as="p" className="cnt-form-sub" dbPath={['contact', 'formSub']} fallback="Fill in the form below and we'll get back to you within 24 hours." />
                    
                    {sent ? (
                        <p style={{ color: '#fff', fontSize: '1.5rem', fontWeight: 700, textAlign: 'center', padding: '40px 0' }}>
                            <InlineEditor as="span" dbPath={['contact', 'formSuccess']} fallback="✅ Message sent! We'll be in touch shortly." />
                        </p>
                    ) : (
                        <form onSubmit={async (e) => { 
                            e.preventDefault(); 
                            const formData = {
                                name: e.target['cnt-name'].value,
                                email: e.target['cnt-email'].value,
                                company: e.target['cnt-company'].value,
                                phone: e.target['cnt-phone'].value,
                                message: e.target['cnt-message'].value,
                            };
                            try {
                                const res = await fetch('/api/leads', {
                                    method: 'POST',
                                    headers: { 'Content-Type': 'application/json' },
                                    body: JSON.stringify(formData)
                                });
                                if (res.ok) setSent(true);
                                else alert("Une erreur s'est produite lors de l'envoi.");
                            } catch(err) {
                                alert("Erreur réseau.");
                            }
                        }}>
                            <div className="cnt-row-2">
                                <div className="cnt-field">
                                    <label htmlFor="cnt-name"><InlineEditor as="span" dbPath={['contact', 'labelName']} fallback="Full Name" /></label>
                                    <input type="text" id="cnt-name" name="cnt-name" placeholder="John Doe" required />
                                </div>
                                <div className="cnt-field">
                                    <label htmlFor="cnt-email"><InlineEditor as="span" dbPath={['contact', 'labelEmail']} fallback="Email Address" /></label>
                                    <input type="email" id="cnt-email" name="cnt-email" placeholder="john@company.com" required />
                                </div>
                            </div>
                            <div className="cnt-row-2">
                                <div className="cnt-field">
                                    <label htmlFor="cnt-company"><InlineEditor as="span" dbPath={['contact', 'labelCompany']} fallback="Company" /></label>
                                    <input type="text" id="cnt-company" name="cnt-company" placeholder="Acme Corp" />
                                </div>
                                <div className="cnt-field">
                                    <label htmlFor="cnt-phone"><InlineEditor as="span" dbPath={['contact', 'labelPhone']} fallback="Phone Number" /></label>
                                    <input type="tel" id="cnt-phone" name="cnt-phone" placeholder="+212 6 00 00 00 00" />
                                </div>
                            </div>
                            <div className="cnt-field">
                                <label htmlFor="cnt-message"><InlineEditor as="span" dbPath={['contact', 'labelMessage']} fallback="How can we help you?" /></label>
                                <textarea id="cnt-message" name="cnt-message" rows="4" placeholder="Tell us about your project or challenge..." required></textarea>
                            </div>
                            <div className="cnt-submit-row">
                                <InlineEditor as="span" className="cnt-note" dbPath={['contact', 'formNote']} fallback="🔒 Your data is 100% secure & never shared." />
                                <button type="submit" className="cnt-btn">
                                    <InlineEditor as="span" dbPath={['contact', 'btnSubmit']} fallback="Send Message" />
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5">
                                        <path d="M5 12h14M12 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </div>
                        </form>
                    )}
                </div>
            </div>
        </div>
    );
}
