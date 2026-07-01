import os
from PIL import Image, ImageDraw, ImageFont

def create_branded_asset(day, prompt, filename):
    # Dimensions: 1200x1200px (High-Fidelity Square)
    w, h = 1200, 1200

    # Colors
    primary = (37, 99, 235)    # #2563EB
    secondary = (15, 23, 42)  # #0F172A
    accent = (16, 185, 129)    # #10B981
    text_color = (255, 255, 255)

    # Create Base
    image = Image.new('RGB', (w, h), color=secondary)
    draw = ImageDraw.Draw(image)

    # Draw Background Gradient (Top-Left primary glow)
    for i in range(400):
        alpha = int(25 * (1 - i/400))
        draw.ellipse([(-200+i, -200+i), (600-i, 600-i)], outline=(37, 99, 235))

    # Border
    draw.rectangle([0, 0, w-1, h-1], outline=primary, width=20)

    # Fonts
    try:
        f_large = ImageFont.load_default(size=70)
        f_med = ImageFont.load_default(size=45)
        f_small = ImageFont.load_default(size=28)
    except:
        f_large = f_med = f_small = ImageFont.load_default()

    # Branding Header
    draw.text((80, 80), "GROWTHPRESS ELITE", fill=primary, font=f_large)
    draw.text((80, 160), f"30-DAY DOMINANCE SERIES | DAY {day:02d}", fill=accent, font=f_med)

    # Decorative Line
    draw.line([80, 240, 400, 240], fill=primary, width=8)

    # Prompt Box
    draw.rectangle([80, 400, w-80, 950], fill=(30, 41, 59), outline=primary, width=2)
    draw.text((110, 430), "VISUAL PROMPT FOR AI GENERATION:", fill=accent, font=f_small)

    # Wrap Text
    words = prompt.split()
    lines = []
    curr = []
    for word in words:
        if len(' '.join(curr + [word])) < 45:
            curr.append(word)
        else:
            lines.append(' '.join(curr))
            curr = [word]
    lines.append(' '.join(curr))

    y = 480
    for line in lines:
        draw.text((110, y), line, fill=text_color, font=f_med)
        y += 65

    # Footer
    draw.text((w/2, 1050), "CAPTURE THE LEADS YOUR COMPETITORS ARE IGNORING", fill=(100, 116, 139), font=f_small, anchor="ms")
    draw.rectangle([w-300, 80, w-80, 180], fill=primary, outline=None)
    draw.text((w-190, 130), "V6.3", fill=text_color, font=f_large, anchor="mm")

    os.makedirs(os.path.dirname(filename), exist_ok=True)
    image.save(filename)

prompts = [
    "Cinematic close-up of a high-end smartphone screen with 50+ unread email notifications, glowing in a dark, minimalist law office. Hyper-realistic, 8k, dramatic lighting.",
    "A high-speed digital clock showing 00:05:00 in a glowing blue neon, reflecting on a glass desk. Professional, sleek, SaaS aesthetic.",
    "Abstract visualization of a human brain interconnected with glowing fiber-optic lines to a 3D Kanban board. Cinematic glassmorphism, depth of field.",
    "Two split screens. Left: A dusty old filing cabinet. Right: The GrowthPress Strategic Command Dashboard on a Pro Display XDR. Clean, modern, high-contrast.",
    "Close-up of a person’s hands typing on a glowing mechanical keyboard, holographic data streams floating above. Cyberpunk professional style.",
    "Silhouette of a CEO looking out a floor-to-ceiling window at a city skyline at dusk. Moody, expensive, high-authority.",
    "A minimalist dark-themed bedroom with a single glowing tablet showing the 'CORE ACTIVE' status of GrowthPress. Peaceful yet powerful.",
    "3D exploded view of a modular geometric structure, each node labeled (Leads, Tasks, Proposals, ROI). Iridescent glass textures, studio lighting.",
    "Macro shot of a high-fidelity Kanban card with a '92% PROBABILITY' score glowing in emerald green. Soft bokeh background.",
    "A sleek financial chart showing an upward trajectory, rendered as a glowing 3D hologram on a marble boardroom table.",
    "Over-the-shoulder shot of a client using a clean, glassmorphic portal on an iPad. Premium textures, natural daylight.",
    "A cinematic 'Intelligence Stream' of text flowing down a dark screen, reminiscent of The Matrix but in professional blue and white.",
    "A stylized map of the world with glowing connections between major cities. Elegant, minimalist, executive.",
    "Blueprint architectural drawing of a modern skyscraper evolving into a digital data structure. Technical, precise, high-detail.",
    "A set of law books on a glass table next to a glowing laptop showing a 'Merit Review' pass. Dramatic shadows, authoritative.",
    "A modern medical consulting room, extremely clean, with a tablet showing a 3D medical triage model. Soft medical green accents.",
    "A residential home with sleek black solar panels reflecting a beautiful sunset. High-end real estate photography style.",
    "Close-up of a high-end watch next to a MacBook showing a signed $25k proposal. Luxury, success, prestige.",
    "Bold white typography '32%' on a deep indigo background with a subtle 'Neural Activity' pulse. Minimalist, impactful.",
    "An empty, modern office with a single glowing server rack. Symbolic of automation and efficiency.",
    "A high-end fountain pen resting on a 'Strategic Roadmap' document. Professional, high-stakes.",
    "A red 'WARNING' light reflecting on a dark screen, transitioning into a green 'OS DEPLOYED' status. Dramatic, high-urgency.",
    "A blur of motion-trailed UI elements snapping into place to form a perfect dashboard. Dynamic, energetic.",
    "A CEO walking confidently through a high-end airport terminal, checking a tablet. Freedom, mobility, control.",
    "Macro shot of a 'Definitive Edition' gold-embossed seal. Premium, exclusive, high-value.",
    "A split visualization: A shrinking red line (Inaction) vs. a massive growing green line (GrowthPress). Clear, mathematical, convincing.",
    "A bold, cinematic shot of a person looking directly into the camera in a high-stakes environment. Confidence, leadership.",
    "A digital calendar flipping rapidly through months, stopping on a glowing current date. Momentum, urgency.",
    "Wide-angle shot of the entire 14-node ecosystem displayed on multiple floating screens. Immersive, futuristic, complete.",
    "A rocket engine ignition or a high-speed data launch. Energy, power, takeoff."
]

for i, prompt in enumerate(prompts):
    day = i + 1
    filename = f"assets/marketing/social-media/day-{day:02d}.png"
    create_branded_asset(day, prompt, filename)
    print(f"Generated {filename}")
