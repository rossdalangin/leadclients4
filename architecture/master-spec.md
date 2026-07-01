# GrowthPress Master Architecture Spec

## High-Level Diagram
Refer to `docs/architecture.md` for the mermaid sequence diagrams of the lead lifecycle.

## Data Layer
The system uses modular meta keys with a `_gp_` prefix to ensure 100% compatibility with the WordPress ecosystem while preventing database pollution.

## Integration Layer
The system is built on a "Hooks First" architecture. All major niche tools (ROI calculators, Symptom Checkers) hook into the `gp_lead_captured` and `gp_async_lead_analysis` actions.
