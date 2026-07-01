# GrowthPress Design Specifications & UI System

## Vision
To create a high-trust, elite-authority atmosphere that justifies high-ticket price points. The UI must feel like a premium SaaS (e.g., Linear, Vercel, or Stripe) rather than a traditional WordPress site.

## 1. Visual Hierarchy
- **Primary Contrast**: Dark Slate (#1E293B) text on ultra-clean off-white (#F8FAFC) backgrounds.
- **Authority Accents**: Growth Blue (#2563EB) for all primary actions.
- **Glassmorphism**: Used to create depth without clutter.
  - `backdrop-filter: blur(40px)`
  - `background: rgba(255, 255, 255, 0.7)`
  - `border: 1px solid rgba(255, 255, 255, 0.3)`

## 2. Typography (The "Authority" Font)
- **Primary Font**: Inter (Sans-serif).
- **Weights**: 400 (Regular), 600 (Semibold), 700 (Bold), 950 (Ultra-Bold).
- **Scale**:
  - H1: 4.5rem / 1.02 line-height (Hero sections).
  - H2: 2.8rem (Section headers).
  - Body: 1.05rem / 1.8 line-height (Max readability).

## 3. Interaction Design
- **Buttons**: 12px border-radius, subtle lift on hover (`translateY(-2px)`), soft shadow.
- **Inputs**: Minimalist borders (#E2E8F0), 12px radius, blue glow on focus.
- **Transitions**: 0.3s ease-in-out for all hover states.
- **Animations**: Use Intersection Observer to "reveal" sections as the user scrolls, creating a polished, modern feel.

## 4. Mobile-First UX
- **Sticky CTAs**: "Book Now" remains reachable at the bottom of the screen on mobile devices.
- **Touch Targets**: Minimum 44x44px for all interactive elements.
- **Full-Screen Menus**: Minimalist overlay for navigation on mobile.

## 5. Executive Dashboard Mockup Specs
- **Header**: 40px high containing the business logo (left) and AI status indicator (right).
- **Executive Summary**: 3 cards (Leads, Bookings, Conv. Rate) using 32px bold numbers and color-coded growth indicators (+/- %).
- **Charts**: Single Chart.js line graph showing 30-day growth trends in primary #2563EB.
- **Kanban Board**:
  - 4 columns (New, Qualified, Booked, Closed).
  - Cards: 15px padding, 4px blue border-left for high-intent, 2px slate for others.
  - Hover: Subtle 5px shadow lift and scale(1.02).
- **Pro Tips Widget**: Dark Slate background (#1E293B) with white text, rotating industry advice every 10 seconds.
- **Activity Feed**: Scrolling list with 9px timestamps and 11px event logs.

## 6. CRM Lead Brief Mockup Specs
- **Lead Header**: Name, Email, and large 'AI Probability' percentage card.
- **Strategic Brief**: 2-column layout.
  - Left: AI-generated closing tactics in a light blue information box.
  - Right: AI-suggested discovery questions in a light orange box.
- **Nudge Widget**: Sidebar list with lightning bolt icons and 12px behavioral advice.
- **Action Footer**: High-contrast blue button: "Generate Strategic AI Proposal".
