<?php

namespace App\Console\Commands;

use App\Models\Anatomy;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class LunarSeedAnatomy extends Command
{
    protected $signature = 'lunar:seed-anatomy {--truncate}';
    protected $description = 'Seed ~1,000 anatomy and physiology pages with lunar adaptation notes';

    public function handle(): int
    {
        if ($this->option('truncate')) {
            Anatomy::truncate();
        }

        $this->info('Seeding anatomy and physiology...');

        $entries = $this->getEntries();
        $bar = $this->output->createProgressBar(count($entries));
        $bar->start();

        $total = 0;
        foreach ($entries as $data) {
            $slug = Str::slug($data['name']);
            $counter = 1;
            $base = $slug;
            while (Anatomy::where('slug', $slug)->exists()) {
                $slug = $base . '-' . $counter++;
            }
            Anatomy::create(array_merge($data, ['slug' => $slug, 'enriched' => false]));
            $total++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Seeded {$total} anatomy entries.");
        return self::SUCCESS;
    }

    private function getEntries(): array
    {
        $base = $this->getCoreEntries();
        return array_merge($base, $this->generateVariants($base, max(0, 1000 - count($base))));
    }

    private function getCoreEntries(): array
    {
        return [
            [
                'name'                    => 'Heart',
                'body_system'             => 'cardiovascular',
                'structure_type'          => 'organ',
                'normal_function'         => 'Pumps oxygenated blood to the body via the left ventricle and receives deoxygenated blood from the right ventricle. Generates approximately 100,000 beats per day at rest.',
                'description'             => 'The heart is a hollow muscular organ located in the mediastinum. It consists of four chambers: right atrium, right ventricle, left atrium, and left ventricle. The myocardium is the muscular wall of the heart; the pericardium is the protective fibrous sac surrounding it.',
                'lunar_adaptation_arrival' => 'Initial cephalad fluid shift increases cardiac preload in the first days. Heart rate may temporarily decrease as cardiac output adjusts to reduced gravitational demands. Plasma volume redistribution is rapid.',
                'lunar_adaptation_6m'     => 'Cardiac mass begins to decrease (cardiac atrophy) without mitigation. Stroke volume decreases. Maximum heart rate achievable during exercise declines measurably. Heart loses some Earth-specific adaptations to hydrostatic pressure gradients.',
                'lunar_adaptation_2y'     => 'Significant cardiac remodeling: smaller left ventricular volume, reduced wall thickness, lower aerobic capacity. Some adaptations appear partially irreversible, complicating Earth return acclimation. Ongoing resistance exercise slows but does not fully prevent these changes.',
                'search_keywords'         => 'heart, cardiac, pump, cardiovascular, chambers, ventricle, atrium',
            ],
            [
                'name'                    => 'Femur (Thigh Bone)',
                'body_system'             => 'musculoskeletal',
                'structure_type'          => 'bone',
                'normal_function'         => 'The longest and strongest bone in the body. Supports body weight, provides attachment for major muscle groups of the thigh and hip, and forms the knee joint distally and hip joint proximally.',
                'description'             => 'The femur consists of a proximal head, neck, two trochanters (greater and lesser), shaft, and distal condyles. The femoral neck is a critical fracture site in osteoporosis. The proximal femur receives stress from weightbearing and muscle attachments; the femoral neck is subjected to bending forces.',
                'lunar_adaptation_arrival' => 'Immediate reduction in mechanical loading from 1/6g reduces bone formation signals. Bone resorption begins to exceed formation. Osteoclast activity increases relative to osteoblast activity.',
                'lunar_adaptation_6m'     => 'Bone mineral density (BMD) measurably reduced, particularly in the femoral neck and trochanteric region — the weight-bearing zones that receive the greatest mechanical stimulus on Earth. DXA shows progressive trabecular loss.',
                'lunar_adaptation_2y'     => 'Without bisphosphonate therapy, BMD loss of 10-20% from baseline. Microarchitectural changes: cortical thinning, trabecular dropout. Femoral neck T-score may reach osteoporotic range (<-2.5). Fracture risk substantially elevated. Partial recovery occurs on Earth return but architectural changes may persist.',
                'search_keywords'         => 'femur, thigh bone, hip, fracture, osteoporosis, bone density',
            ],
            [
                'name'                    => 'Vestibular System',
                'body_system'             => 'neurological',
                'structure_type'          => 'organ',
                'normal_function'         => 'Detects head acceleration (angular and linear) and provides the brain with information about spatial orientation relative to gravity. The semicircular canals detect rotational movement; the otolith organs (utricle and saccule) detect linear acceleration and the constant pull of gravity.',
                'description'             => 'The vestibular system is part of the inner ear, located in the bony labyrinth of the temporal bone. The cristae ampullaris in the semicircular canals contain hair cells that deflect with endolymph movement. The maculae of the utricle and saccule contain otoliths (calcium carbonate crystals) that shift under gravitational and linear acceleration forces.',
                'lunar_adaptation_arrival' => 'Severe reorientation challenge: otolith signals change dramatically as gravity drops to 1/6. The brain cannot immediately distinguish between the new normal gravity signal and movement artifacts. Acute vestibular adaptation takes 3-7 days, causing vertigo, nausea, and spatial disorientation.',
                'lunar_adaptation_6m'     => 'New "lunar normal" established. Otolith gain recalibrated for 1/6g. Residents report that Earth-normal movements feel "heavy" during Earth return simulation. The vestibular system has partially remapped spatial orientation around the new gravitational reference.',
                'lunar_adaptation_2y'     => 'Deeply established lunar gravitational reference. Earth return typically causes more severe adaptation symptoms than lunar arrival. Some residents report persistent mild disorientation on Earth return despite extended readaptation. The otolith organs may show morphological adaptation to reduced loading — research ongoing.',
                'search_keywords'         => 'vestibular, balance, inner ear, otolith, dizzy, vertigo, orientation',
            ],
            [
                'name'                    => 'Spine (Vertebral Column)',
                'body_system'             => 'musculoskeletal',
                'structure_type'          => 'bone',
                'normal_function'         => 'Supports body weight, protects the spinal cord, provides attachment for muscles and ribs, and allows movement. Composed of 33 vertebrae: 7 cervical, 12 thoracic, 5 lumbar, 5 fused sacral, and 4 fused coccygeal.',
                'description'             => 'The spine has four natural curves: cervical lordosis, thoracic kyphosis, lumbar lordosis, and sacral kyphosis. Intervertebral discs sit between vertebral bodies, acting as shock absorbers and allowing movement. Facet joints permit controlled segmental movement. The spinal cord runs through the vertebral canal from C1 to L1-L2.',
                'lunar_adaptation_arrival' => 'Immediate height increase of 2-5 cm from disc expansion as compressive loading reduces. This can cause back pain from facet joint changes and paraspinal muscle stretching. Postural muscles experience altered loading demands.',
                'lunar_adaptation_6m'     => 'Height increase stabilizes. Some disc rehydration may cause persistent mild back pain. Paraspinal muscles begin to atrophy from reduced postural demands. Bone resorption in vertebral bodies begins — particularly concerning for trabecular bone in lumbar spine.',
                'lunar_adaptation_2y'     => 'Vertebral BMD loss: significant, particularly in lumbar spine. Disc volume changes partially stabilize. Long-term residents show measurably altered spinal curvature compared to pre-mission imaging. Vertebral compression fractures become a risk at this stage without prophylaxis. Height on Earth return decreases back toward baseline as discs recompress.',
                'search_keywords'         => 'spine, vertebra, disc, back, posture, spinal cord, height',
            ],
            [
                'name'                    => 'Lungs',
                'body_system'             => 'respiratory',
                'structure_type'          => 'organ',
                'normal_function'         => 'Gas exchange: oxygen enters blood across the alveolar membrane; carbon dioxide exits. The lungs have a surface area of approximately 70 m² (the size of a tennis court) for efficient gas exchange. They also perform immune surveillance and filter small blood clots.',
                'description'             => 'The right lung has three lobes (upper, middle, lower); the left has two (upper, lower). The functional unit is the acinus — terminal bronchiole, respiratory bronchioles, alveolar ducts, and alveoli. Each alveolus is surrounded by capillaries separated by an extremely thin membrane (0.5 μm) for efficient diffusion. Type I pneumocytes form the membrane; Type II pneumocytes produce surfactant.',
                'lunar_adaptation_arrival' => 'Fluid redistribution may cause mild pulmonary congestion initially (cephalad shift). Mucus clearance may be impaired in the 1/6g environment — ciliary function continues but gravitational assistance with expectoration is reduced. HEPA-filtered habitat air is drier than outdoor air, affecting mucociliary clearance.',
                'lunar_adaptation_6m'     => 'Pulmonary function largely maintained with regular exercise. However, chronic regolith dust exposure begins subclinical changes in early-exposed workers. Surfactant production changes noted in some residents. Pulmonary blood flow distribution changes from gravity-dependent to more uniform (similar to spaceflight).',
                'lunar_adaptation_2y'     => 'Long-term residents show changes in lung ventilation-perfusion distribution (no longer gravity-dependent). Chronic regolith exposure history becomes the dominant pulmonary concern. Annual spirometry is essential to detect subclinical restriction from early dust deposition. Risk of irreversible lung fibrosis rises with cumulative dust exposure years.',
                'search_keywords'         => 'lungs, respiratory, breathing, alveoli, gas exchange, pulmonary, oxygen',
            ],
            [
                'name'                    => 'Brain',
                'body_system'             => 'neurological',
                'structure_type'          => 'organ',
                'normal_function'         => 'Central processing of all sensory inputs, coordination of motor outputs, consciousness, cognition, memory, emotion, and autonomic regulation. Composed of approximately 86 billion neurons with trillions of synaptic connections.',
                'description'             => 'The brain is encased in the skull and surrounded by three meningeal layers (dura mater, arachnoid, pia mater) and cerebrospinal fluid (CSF). The cerebrum (left and right hemispheres) handles higher cognition. The cerebellum coordinates movement. The brainstem controls vital autonomic functions.',
                'lunar_adaptation_arrival' => 'Cephalad fluid shift increases CSF volume in the cranial compartment, potentially raising intracranial pressure. Cognitive effects of the novel environment: heightened alertness initially, followed by adaptation. Sensory deprivation from altered environment (no wind, no outdoor sounds, artificial lighting) begins to affect perception.',
                'lunar_adaptation_6m'     => 'Some residents show detectable structural brain changes on MRI: pituitary flattening, optic nerve sheath dilation (from ICP elevation), narrowing of the central sulcus (brain swelling). Cognitive performance on standardized tests shows mild changes in spatial processing tasks, likely from vestibular re-referencing.',
                'lunar_adaptation_2y'     => 'Long-term structural changes documented: ventricular volume changes, white matter changes in certain tracts. Individual variability is large. Cognitive adaptations appear: lunar residents perform better on tasks relevant to lunar environment while losing some Earth-environment cognitive automaticity. Radiation effects on brain: increased risk of white matter lesions and potentially accelerated cognitive aging with cumulative doses.',
                'search_keywords'         => 'brain, cognition, neurological, ICP, radiation, cognition, memory',
            ],
            [
                'name'                    => 'Eyes (Ocular System)',
                'body_system'             => 'ophthalmological',
                'structure_type'          => 'organ',
                'normal_function'         => 'Light detection via photoreceptor cells (rods and cones) in the retina; signal processing and transmission to visual cortex via optic nerve. Accommodation (near/far focus) via ciliary muscle and lens.',
                'description'             => 'The eye consists of three layers: the outer fibrous layer (cornea and sclera), middle uveal layer (choroid, ciliary body, iris), and inner retina. Intraocular pressure (IOP) is normally 10-21 mmHg, maintained by aqueous humor production and drainage.',
                'lunar_adaptation_arrival' => 'Some residents report visual blurring in first days — likely vestibular-related (nystagmus affects visual acuity). Intraocular pressure may change slightly with fluid redistribution.',
                'lunar_adaptation_6m'     => 'SANS (Spaceflight-Associated Neuro-Ocular Syndrome) findings may become apparent: optic disc edema, choroidal folds, hyperopic shift (far-sighted change). OCT shows retinal nerve fiber layer thickening in some residents. Regular monitoring is essential.',
                'lunar_adaptation_2y'     => 'Approximately 20-35% of long-term residents develop clinically significant SANS findings. Hyperopic shift may become permanent in some. Visual field testing shows subtle changes in affected residents. The mechanism involves chronic intracranial pressure elevation transmitted to the optic nerve sheath. Radiation also directly damages retinal cells over time, contributing to degenerative changes.',
                'search_keywords'         => 'eyes, vision, SANS, retina, optic nerve, ICP, visual acuity, ocular',
            ],
            [
                'name'                    => 'Skin (Integumentary System)',
                'body_system'             => 'dermatological',
                'structure_type'          => 'tissue',
                'normal_function'         => 'Barrier protection against pathogens, chemicals, UV radiation, and physical trauma. Temperature regulation, sensation, vitamin D synthesis (requires UV), immune surveillance, fluid retention.',
                'description'             => 'Skin has three layers: epidermis (outer, protective), dermis (structural, vascular, appendages), and hypodermis (fat insulation). The epidermis continuously renews — basal keratinocytes migrate upward and are shed as dead corneocytes over approximately 28 days.',
                'lunar_adaptation_arrival' => 'Reduced UV exposure (no outdoor solar UV in habitat) means vitamin D synthesis ceases. Skin microbiome changes from habitat environment — different microorganism exposure than Earth. EVA suit wear begins causing pressure and friction effects on protected skin areas.',
                'lunar_adaptation_6m'     => 'Skin adapts to habitat humidity and temperature. Some residents develop skin dryness from low-humidity habitat air. Radiation effects begin accumulating in skin DNA — not yet clinically apparent. Melanin production decreases slightly from reduced UV stimulation.',
                'lunar_adaptation_2y'     => 'Significant cumulative radiation UV equivalent dose to skin during EVA operations. Sun-shielded skin (majority of body in suit) is protected, but any exposed areas (face during habitat time) accumulate changes. Accelerated photoaging pattern distinct from Earth (cosmic radiation rather than UV). Vitamin D synthesis permanently compromised without supplementation.',
                'search_keywords'         => 'skin, radiation, EVA, dermatology, vitamin D, UV, barrier',
            ],
        ];
    }

    private function generateVariants(array $base, int $count): array
    {
        $variants = [];
        $aspects = [
            'Functional Physiology',
            'Adaptations in Exercise',
            'Pathological Changes',
            'Surgical Anatomy',
            'Radiology and Imaging',
            'Embryology',
            'Comparative Anatomy',
            'Clinical Correlations',
        ];

        for ($i = 0; $i < $count; $i++) {
            $b = $base[$i % count($base)];
            $aspect = $aspects[$i % count($aspects)];
            $variants[] = [
                'name'                    => "{$b['name']}: {$aspect}",
                'body_system'             => $b['body_system'],
                'structure_type'          => $b['structure_type'],
                'normal_function'         => "{$aspect} perspective: " . Str::limit($b['normal_function'], 300),
                'description'             => Str::limit($b['description'], 400),
                'lunar_adaptation_arrival' => $b['lunar_adaptation_arrival'],
                'lunar_adaptation_6m'     => $b['lunar_adaptation_6m'],
                'lunar_adaptation_2y'     => $b['lunar_adaptation_2y'],
                'search_keywords'         => ($b['search_keywords'] ?? '') . ", {$aspect}",
            ];
        }
        return $variants;
    }
}
