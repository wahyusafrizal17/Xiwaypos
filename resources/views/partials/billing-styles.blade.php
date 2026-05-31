<style>
    .billing-page {
        width: 100%;
    }

    .billing-status {
        position: relative;
        overflow: hidden;
        border-radius: 18px;
        background: linear-gradient(135deg, #111111 0%, #1a1a1a 100%);
        color: #fff;
        padding: 28px 28px 24px;
        margin-bottom: 40px;
        box-shadow: 0 20px 50px -24px rgba(0, 0, 0, 0.45);
    }
    .billing-status::after {
        content: '';
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        height: 3px;
        background: linear-gradient(90deg, #E01010, #ff4d4d);
    }
    .billing-status-grid {
        display: grid;
        gap: 24px;
        align-items: start;
    }
    @media (min-width: 768px) {
        .billing-status-grid {
            grid-template-columns: 1fr auto;
        }
    }
    .billing-status-eyebrow {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: rgba(255, 255, 255, 0.45);
        margin-bottom: 8px;
    }
    .billing-status-title {
        font-size: clamp(22px, 3vw, 28px);
        font-weight: 700;
        letter-spacing: -0.03em;
        line-height: 1.15;
        margin: 0;
    }
    .billing-status-meta {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
        margin-top: 14px;
        font-size: 14px;
        color: rgba(255, 255, 255, 0.72);
    }
    .billing-status-meta strong {
        color: #fff;
        font-weight: 600;
    }
    .billing-status-pill {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }
    .billing-status-pill.is-trial,
    .billing-status-pill.is-active {
        background: rgba(224, 16, 16, 0.2);
        color: #ffb4b4;
        border: 1px solid rgba(224, 16, 16, 0.35);
    }
    .billing-status-pill.is-warning {
        background: rgba(217, 119, 6, 0.18);
        color: #fcd34d;
        border: 1px solid rgba(217, 119, 6, 0.35);
    }
    .billing-status-pill.is-danger {
        background: rgba(220, 38, 38, 0.18);
        color: #fca5a5;
        border: 1px solid rgba(220, 38, 38, 0.35);
    }
    .billing-status-pill.is-neutral {
        background: rgba(255, 255, 255, 0.08);
        color: rgba(255, 255, 255, 0.7);
        border: 1px solid rgba(255, 255, 255, 0.12);
    }
    .billing-status-note {
        margin-top: 12px;
        font-size: 13px;
        color: rgba(255, 255, 255, 0.55);
        max-width: 36rem;
        line-height: 1.55;
    }
    .billing-status-note.is-alert {
        color: #fecaca;
    }

    .billing-trial-card {
        min-width: 220px;
        padding: 16px 18px;
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    .billing-trial-top {
        display: flex;
        align-items: baseline;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 10px;
    }
    .billing-trial-days {
        font-size: 28px;
        font-weight: 800;
        letter-spacing: -0.04em;
        line-height: 1;
        color: #fff;
    }
    .billing-trial-days span {
        font-size: 13px;
        font-weight: 600;
        color: rgba(255, 255, 255, 0.5);
        margin-left: 4px;
    }
    .billing-trial-label {
        font-size: 12px;
        font-weight: 600;
        color: rgba(255, 255, 255, 0.55);
    }
    .billing-trial-bar {
        height: 6px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.1);
        overflow: hidden;
    }
    .billing-trial-bar-fill {
        height: 100%;
        border-radius: inherit;
        background: linear-gradient(90deg, #E01010, #ff5555);
        transition: width 0.4s ease;
    }

    .billing-section {
        margin-bottom: 28px;
    }
    .billing-section-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--vx-text-mute);
        margin-bottom: 8px;
    }
    .billing-section-label::before {
        content: '';
        width: 18px;
        height: 2px;
        background: var(--vx-primary);
        border-radius: 1px;
    }
    .billing-section-title {
        margin: 0;
        font-size: 22px;
        font-weight: 700;
        letter-spacing: -0.03em;
        color: var(--vx-text);
    }
    .billing-section-desc {
        margin: 6px 0 0;
        font-size: 14px;
        color: var(--vx-text-soft);
        line-height: 1.55;
    }

    .billing-plans {
        display: grid;
        gap: 20px;
    }
    @media (min-width: 768px) {
        .billing-plans {
            grid-template-columns: repeat(3, 1fr);
            align-items: stretch;
        }
    }

    .billing-plan {
        position: relative;
        display: flex;
        flex-direction: column;
        background: #fff;
        border: 1px solid var(--vx-border-soft);
        border-radius: 18px;
        padding: 28px 24px 24px;
        box-shadow: var(--vx-shadow-sm);
        transition: transform 0.22s ease, box-shadow 0.22s ease, border-color 0.22s ease;
    }
    .billing-plan:hover {
        transform: translateY(-4px);
        box-shadow: var(--vx-shadow);
    }
    .billing-plan.is-current {
        border-color: var(--vx-primary);
        box-shadow: 0 12px 40px -16px rgba(224, 16, 16, 0.35);
    }
    .billing-plan.is-featured:not(.is-current) {
        border-color: rgba(224, 16, 16, 0.25);
        background: linear-gradient(180deg, #fff 0%, #fffafa 100%);
    }
    .billing-plan-badge {
        position: absolute;
        top: -11px;
        left: 50%;
        transform: translateX(-50%);
        padding: 5px 14px;
        border-radius: 999px;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        white-space: nowrap;
    }
    .billing-plan-badge.is-popular {
        background: var(--vx-primary);
        color: #fff;
        box-shadow: 0 4px 14px -4px rgba(224, 16, 16, 0.55);
    }
    .billing-plan-badge.is-current {
        background: var(--vx-black);
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.12);
    }
    .billing-plan-name {
        font-size: 13px;
        font-weight: 600;
        color: var(--vx-text-mute);
        letter-spacing: 0.02em;
        margin-bottom: 8px;
    }
    .billing-plan-price {
        font-size: 34px;
        font-weight: 800;
        letter-spacing: -0.04em;
        line-height: 1;
        color: var(--vx-text);
    }
    .billing-plan-price small {
        font-size: 14px;
        font-weight: 500;
        color: var(--vx-text-mute);
        letter-spacing: 0;
    }
    .billing-plan-period {
        margin-top: 6px;
        font-size: 13px;
        color: var(--vx-text-soft);
        line-height: 1.45;
    }
    .billing-plan-period-full {
        text-decoration: line-through;
        color: var(--vx-text-mute);
        margin-right: 4px;
    }
    .billing-plan-save {
        margin-top: 8px;
        font-size: 12px;
        font-weight: 700;
        color: var(--vx-primary);
    }
    .billing-plan-save span {
        font-weight: 600;
        opacity: 0.85;
    }
    .billing-plan-divider {
        height: 1px;
        background: var(--vx-border-soft);
        margin: 22px 0 20px;
    }
    .billing-plan-features {
        list-style: none;
        margin: 0;
        padding: 0;
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 11px;
    }
    .billing-plan-features li {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        font-size: 13.5px;
        line-height: 1.45;
        color: var(--vx-text-soft);
    }
    .billing-plan-check {
        flex-shrink: 0;
        width: 18px;
        height: 18px;
        margin-top: 1px;
        border-radius: 50%;
        background: var(--vx-primary-soft);
        border: 1px solid rgba(224, 16, 16, 0.15);
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .billing-plan-check svg {
        width: 10px;
        height: 10px;
        stroke: var(--vx-primary);
        fill: none;
        stroke-width: 2.5;
    }
    .billing-plan-cta {
        margin-top: 24px;
    }
    .billing-plan-cta .vx-btn {
        width: 100%;
        padding-top: 12px;
        padding-bottom: 12px;
    }

    .billing-footnote {
        margin-top: 32px;
        padding: 16px 20px;
        border-radius: 14px;
        background: var(--vx-primary-soft);
        border: 1px solid rgba(224, 16, 16, 0.12);
        text-align: center;
        font-size: 13px;
        color: var(--vx-primary-text);
        line-height: 1.55;
    }
    .billing-footnote strong {
        color: var(--vx-text);
    }

    .billing-alert {
        margin-bottom: 24px;
        padding: 14px 16px;
        border-radius: 12px;
        border: 1px solid #fecaca;
        background: #fef2f2;
        font-size: 14px;
        color: #991b1b;
    }
    .billing-alert.is-success {
        border-color: #bbf7d0;
        background: #f0fdf4;
        color: #166534;
    }
    .billing-alert.is-pending {
        border-color: rgba(224, 16, 16, 0.2);
        background: var(--vx-primary-soft);
        color: var(--vx-primary-text);
    }

    .billing-modal-overlay {
        position: fixed;
        inset: 0;
        z-index: 60;
        background: rgba(17, 17, 17, 0.55);
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    .billing-modal {
        width: 100%;
        max-width: 520px;
        max-height: min(90vh, 720px);
        overflow-y: auto;
        background: #fff;
        border-radius: 18px;
        box-shadow: var(--vx-shadow-lg);
        border: 1px solid var(--vx-border-soft);
    }
    .billing-modal-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        padding: 22px 22px 0;
    }
    .billing-modal-head h3 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: var(--vx-text);
        letter-spacing: -0.02em;
    }
    .billing-modal-head p {
        margin: 4px 0 0;
        font-size: 13px;
        color: var(--vx-text-soft);
    }
    .billing-modal-close {
        width: 34px;
        height: 34px;
        border: 0;
        border-radius: 10px;
        background: var(--vx-bg);
        color: var(--vx-text-soft);
        cursor: pointer;
        font-size: 20px;
        line-height: 1;
    }
    .billing-modal-close:hover {
        background: var(--vx-primary-soft);
        color: var(--vx-primary);
    }
    .billing-modal-body {
        padding: 20px 22px 22px;
    }
    .billing-bank-box {
        padding: 14px 16px;
        border-radius: 12px;
        background: var(--vx-bg);
        border: 1px solid var(--vx-border-soft);
        margin-bottom: 18px;
        font-size: 13px;
        color: var(--vx-text-soft);
        line-height: 1.55;
    }
    .billing-bank-box strong {
        display: block;
        color: var(--vx-text);
        font-size: 15px;
        margin-bottom: 4px;
    }
    .billing-cycle-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-bottom: 16px;
    }
    .billing-cycle-option {
        position: relative;
        cursor: pointer;
    }
    .billing-cycle-option input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }
    .billing-cycle-card {
        display: block;
        padding: 12px 14px;
        border-radius: 12px;
        border: 1.5px solid var(--vx-border);
        background: #fff;
        transition: border-color 0.15s, background 0.15s;
    }
    .billing-cycle-option input:checked + .billing-cycle-card {
        border-color: var(--vx-primary);
        background: var(--vx-primary-soft);
    }
    .billing-cycle-card strong {
        display: block;
        font-size: 13px;
        color: var(--vx-text);
    }
    .billing-cycle-card span {
        display: block;
        margin-top: 2px;
        font-size: 12px;
        color: var(--vx-text-soft);
    }
    .billing-cycle-badge {
        display: inline-block;
        margin-bottom: 6px;
        padding: 2px 8px;
        border-radius: 999px;
        background: var(--vx-primary);
        color: #fff;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }
    .billing-cycle-sub {
        display: block;
        margin-top: 4px !important;
        font-size: 11px !important;
        color: var(--vx-primary-text) !important;
        line-height: 1.4;
    }
    .billing-modal-amount {
        margin-bottom: 16px;
        padding: 12px 14px;
        border-radius: 12px;
        background: var(--vx-primary-soft);
        font-size: 13px;
        color: var(--vx-primary-text);
    }
    .billing-modal-strike {
        margin: 0 0 4px;
        font-size: 12px;
        color: var(--vx-text-mute);
    }
    .billing-modal-strike span {
        text-decoration: line-through;
    }
    .billing-modal-amount strong {
        font-size: 18px;
        color: var(--vx-text);
    }
    .billing-modal-actions {
        display: flex;
        gap: 10px;
        margin-top: 18px;
    }
    .billing-modal-actions .vx-btn {
        flex: 1;
    }
</style>
