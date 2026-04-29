<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class LunarSeedArticles extends Command
{
    protected $signature = 'lunar:seed-articles {--truncate}';
    protected $description = 'Seed ~2,000 research articles for the Lunar Medical Research Cooperative';

    public function handle(): int
    {
        if ($this->option('truncate')) {
            Article::truncate();
        }

        $this->info('Seeding research articles...');

        $articles = $this->getArticles();
        $bar = $this->output->createProgressBar(count($articles));
        $bar->start();

        $total = 0;
        foreach ($articles as $data) {
            $slug = Str::slug($data['title']);
            $counter = 1;
            $base = $slug;
            while (Article::where('slug', $slug)->exists()) {
                $slug = $base . '-' . $counter++;
            }
            Article::create(array_merge($data, [
                'slug'    => $slug,
                'enriched'=> false,
            ]));
            $total++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Seeded {$total} articles.");
        return self::SUCCESS;
    }

    private function getArticles(): array
    {
        $base = $this->getCoreArticles();
        return array_merge($base, $this->generateVariants($base, max(0, 2000 - count($base))));
    }

    private function getCoreArticles(): array
    {
        $authors = [
            ['name' => 'Dr. Chen Wei',         'affiliation' => 'Lunar Medical Research Cooperative'],
            ['name' => 'Dr. Fatima Al-Rashid', 'affiliation' => 'Mare Imbrium Medical Institute'],
            ['name' => 'Dr. James Okafor',     'affiliation' => 'Shackleton Crater Health Sciences'],
            ['name' => 'Dr. Yuki Tanaka',      'affiliation' => 'Tycho Base Medical Center'],
            ['name' => 'Dr. Priya Sharma',     'affiliation' => 'Copernicus Station Clinic'],
            ['name' => 'Dr. Elena Volkova',    'affiliation' => 'International Lunar Medical Commission'],
            ['name' => 'Dr. Michael Oduya',    'affiliation' => 'Armstrong Settlement Health Services'],
            ['name' => 'Dr. Sarah Blackwood',  'affiliation' => 'Oceanus Procellarum Research Station'],
            ['name' => 'Dr. Kwame Asante',     'affiliation' => 'Tycho Crater Teaching Hospital'],
            ['name' => 'Dr. Marta Kowalska',   'affiliation' => 'European Lunar Health Authority'],
            ['name' => 'Dr. Raj Subramaniam',  'affiliation' => 'Aristarchus Plateau Clinic'],
            ['name' => 'Dr. Noa Ben-David',    'affiliation' => 'Lunar Emergency Medicine Consortium'],
        ];

        return [
            [
                'title'            => 'Longitudinal Study of Bone Mineral Density in Lunar Residents: A 24-Month Follow-Up',
                'research_type'    => 'clinical_trial',
                'author_name'      => $authors[0]['name'],
                'author_affiliation' => $authors[0]['affiliation'],
                'abstract'         => 'We present longitudinal DXA data from 47 lunar residents over 24 months, comparing bone mineral density changes in residents following standard exercise protocol versus augmented bisphosphonate therapy. Residents on standard protocol alone showed 8.2% femoral neck BMD loss at 12 months. The bisphosphonate group showed only 2.1% loss, with partial recovery after protocol completion.',
                'content'          => "Introduction\n\nThe preservation of bone mineral density (BMD) represents one of the most critical challenges in long-duration lunar habitation. Prior studies from orbital spaceflight have documented BMD losses of 1-2% per month in weight-bearing bones without countermeasures — losses that, if extrapolated to multi-year lunar missions, pose a substantial fragility fracture risk.\n\nThe lunar environment offers a partial mitigation compared to zero-gravity spaceflight: 1/6 Earth gravity still provides some mechanical loading stimulus to bone. However, early data from the first generation of lunar residents suggests BMD loss rates of 0.4-0.8% per month, still well above any natural rate of bone loss in ambulatory adults.\n\nMethods\n\nForty-seven adult residents of three lunar habitats were enrolled with informed consent. Participants were randomized to: (1) Standard exercise protocol alone (minimum 90 minutes resistance exercise per day), or (2) Standard exercise plus zoledronic acid 5 mg IV annually. DXA measurements were performed at baseline, 6, 12, and 24 months at the femoral neck, total hip, and lumbar spine.\n\nResults\n\nAt 24 months, the exercise-only group showed femoral neck BMD loss of 8.2% (±2.3%). Lumbar spine loss was less pronounced at 4.1% (±1.8%), reflecting the partial loading from 1/6g posture. The bisphosphonate group showed femoral neck BMD changes of +0.3% (±1.2%) at 24 months, representing effective stabilization.\n\nDiscussion\n\nThese findings support early bisphosphonate intervention for lunar residents on missions expected to exceed 12 months. The cost-benefit ratio is strongly favorable: the risk of hip fracture in an osteoporotic lunar resident performing mining operations is unacceptable, and the treatment is well-tolerated.\n\nConclusion\n\nZoledronic acid annual infusion combined with standard exercise protocol effectively preserves BMD in lunar residents. We recommend this as standard prophylactic care for all residents with missions exceeding 12 months.",
                'journal_name'     => 'Journal of Lunar Medicine',
                'volume_issue'     => 'Vol. 3, No. 2',
                'published_date'   => '2029-06-15',
                'keywords'         => 'bone mineral density, osteoporosis, bisphosphonate, lunar, DXA, femoral neck',
                'body_system'      => 'musculoskeletal',
                'featured'         => true,
            ],
            [
                'title'            => 'Earth-Sickness Syndrome: Clinical Characterization and Treatment Outcomes in 200 Lunar Residents',
                'research_type'    => 'case_study',
                'author_name'      => $authors[1]['name'],
                'author_affiliation' => $authors[1]['affiliation'],
                'abstract'         => 'Terra Siderans — Earth-sickness syndrome — has emerged as the most prevalent mental health condition in long-duration lunar residents. We present clinical data from 200 residents showing a distinct syndrome pattern different from traditional homesickness or major depression, characterized by grief for the Earth as a lost ecosystem rather than a lost home.',
                'content'          => "Clinical Presentation\n\nEarth-sickness syndrome presents with a characteristic triad: (1) persistent longing for Earth that is focused on non-social aspects — weather, gravity, open spaces, natural sounds — rather than or in addition to personal relationships; (2) vivid and emotionally intense dreams of Earth environments; and (3) difficulty forming emotional attachment to the lunar environment as 'home.'\n\nThis distinguishes it from standard homesickness, which centers on lost social connections. Residents often describe grieving for sensory experiences they cannot replicate: rain, wind, the weight of their bodies in full gravity, the smell of soil.\n\nThe Heinlein-era colonial fiction captured something psychologically accurate: the deep cost of cutting ties with Earth's gravitational and biological heritage. We propose the name Terra Siderans — 'starved longing for Earth' — to distinguish this syndrome and honor the literary tradition that anticipated it.\n\nTreatment Outcomes\n\nThe most effective interventions in our cohort were: (1) High-bandwidth, high-quality Earth communication (video calls with family combined with live streaming of Earth environments), with an effect size of 0.6 standard deviations on symptom scales; (2) VR Earth environments (multiple biome simulations including forests, beaches, and urban environments), effect size 0.4; (3) Habitat windows with direct Earth view, reported as most meaningful by residents even when Earth was below the lunar horizon.\n\nPharmacological interventions (SSRIs) were effective for comorbid major depression but did not address the core Earth-sickness syndrome specifically.\n\nConclusion\n\nEarth-sickness syndrome is a genuine and prevalent condition requiring dedicated clinical attention and habitat design considerations. The allocation of communication bandwidth and Earth-view windows represents a significant return on investment for resident psychological wellbeing.",
                'journal_name'     => 'Lunar Psychiatry and Behavioral Health',
                'volume_issue'     => 'Vol. 1, No. 1',
                'published_date'   => '2028-03-01',
                'keywords'         => 'Earth-sickness, Terra Siderans, mental health, isolation, lunar psychology',
                'body_system'      => 'psychological',
                'featured'         => true,
            ],
            [
                'title'            => 'Regolith Dust Inhalation: Early Respiratory Outcomes in 350 EVA Workers',
                'research_type'    => 'clinical_trial',
                'author_name'      => $authors[2]['name'],
                'author_affiliation' => $authors[2]['affiliation'],
                'abstract'         => 'Lunar regolith presents a unique respiratory hazard combining the physical risks of ultrafine particle inhalation with the chemical reactivity of unweathered silicate material. We present 24-month spirometry data from 350 EVA workers with quantified dust exposure, demonstrating a dose-response relationship between cumulative EVA hours and FVC decline.',
                'content'          => "The risk of regolith dust inhalation as a long-term occupational health hazard was recognized theoretically before the first permanent lunar residents arrived, but the practical magnitude of this risk — even with rigorous decontamination protocols — is only now becoming clear.\n\nOur study followed 350 EVA workers at five habitats over 24 months. Cumulative EVA exposure was tracked, and we quantified decontamination protocol adherence. Spirometry was performed every 6 months.\n\nFindings\n\nIn the highest-exposure quartile (>500 EVA hours over 24 months), FVC decline was 3.2% from baseline — approximately twice the rate in matched controls with <100 EVA hours. Diffusion capacity (DLCO) was reduced in 12% of the high-exposure group.\n\nBreath condensate analysis showed elevated markers of oxidative stress (8-isoprostane, H2O2) proportional to EVA exposure, consistent with reactive oxygen species from regolith particle surface chemistry.\n\nDecontamination quality strongly moderated the relationship: high-adherence decontamination reduced FVC decline by approximately 40%. This is the strongest evidence to date that protocol adherence significantly mitigates, though does not eliminate, the risk.\n\nImplications\n\nEVA hour limits for cumulative career exposure are urgently needed. We recommend a career EVA exposure limit of 2,000 hours, with mandatory annual spirometry and retirement from EVA operations if FVC decline exceeds 5% from baseline.\n\nThe analogy to early industrial silicosis is apt: the clinical cases we are seeing now will become far more prevalent in a generation if prevention is not prioritized today.",
                'journal_name'     => 'Lunar Occupational Health',
                'volume_issue'     => 'Vol. 2, No. 4',
                'published_date'   => '2029-09-20',
                'keywords'         => 'regolith, dust, spirometry, FVC, occupational health, EVA, pneumoconiosis',
                'body_system'      => 'respiratory',
                'featured'         => true,
            ],
            [
                'title'            => 'Predictive Epidemiology in Closed Habitats: Mathematical Modeling of Respiratory Outbreak Propagation',
                'research_type'    => 'policy',
                'author_name'      => $authors[3]['name'],
                'author_affiliation' => $authors[3]['affiliation'],
                'abstract'         => 'We present mathematical models for respiratory pathogen spread in closed lunar habitats, demonstrating that standard R0 values dramatically underestimate transmission in recirculated air environments. Our models show that a single infectious arrival in a 50-person habitat can infect the majority of residents within 96 hours without intervention. We propose evidence-based quarantine and air management protocols.',
                'content'          => "The concept of predictive epidemiology — using mathematical models to forecast outbreak patterns before they occur — has been theorized since the early 21st century. In the context of lunar habitats, it is not merely academically interesting but operationally essential: a respiratory outbreak in a 50-person habitat with a 2-week Earth resupply window is a genuine mission-threatening event.\n\nOur compartmental models (SEIR framework modified for closed air circulation) show that standard calculation of R0 from community transmission data dramatically underestimates propagation in shared air-circulation habitats. The effective R0 for influenza in a closed lunar habitat with standard HVAC (without HEPA filtration) is approximately 8-12, compared to 1.2-3 in community settings.\n\nThe implications are stark: without quarantine and air management protocols, a single infectious arrival would infect the majority of a 50-person habitat within 4-6 days.\n\nInterventions modeled:\n1. New arrival quarantine (7 days in isolated quarters): reduces peak incidence by 73%\n2. HEPA filtration upgrade: reduces effective R0 to ~3 (comparable to outdoor community spread)\n3. Combination (quarantine + HEPA + symptomatic isolation): maintains outbreak size <10% of population in 95% of simulations\n\nConclusion\n\nWe recommend mandatory implementation of all three interventions at all permanently inhabited lunar facilities. The cost of a habitat-wide respiratory outbreak — in both human health terms and mission impact — far exceeds the modest investment in these prevention measures.",
                'journal_name'     => 'Lunar Public Health Journal',
                'volume_issue'     => 'Vol. 1, No. 2',
                'published_date'   => '2027-11-15',
                'keywords'         => 'epidemiology, outbreak, mathematical model, respiratory, quarantine, closed habitat',
                'body_system'      => 'infectious',
                'featured'         => false,
            ],
            [
                'title'            => 'Improvised Medical Protocol Development for Multi-Day EVA Operations: The Botanical First Aid Framework',
                'research_type'    => 'equipment_review',
                'author_name'      => $authors[4]['name'],
                'author_affiliation' => $authors[4]['affiliation'],
                'abstract'         => 'Extended EVA operations in remote lunar surface areas may require managing medical emergencies without immediate medical bay access. This paper describes the Botanical First Aid Framework — a protocol system for improvised medical management using available resources, developed from analysis of 42 remote EVA medical incidents.',
                'content'          => "The challenge of managing medical emergencies during multi-day EVA operations far from the main habitat is one of the most underaddressed problems in lunar occupational medicine. The focus of most medical planning on habitat-based care leaves a significant gap.\n\nWe analyzed 42 medical incidents occurring during EVA operations more than 4 hours from the nearest medical bay, drawing on incident reports from five lunar habitats over a 3-year period. Incident types included musculoskeletal trauma (38%), equipment malfunction-related injury (24%), cardiovascular events (15%), and respiratory incidents (12%).\n\nThe term 'Botanical First Aid' emerged from the improvised nature of field medicine in resource-limited environments — recalling the historical practice of botanists on solo expeditions who developed systematic approaches to self-treatment from available materials. In the lunar context, available materials are limited but well-characterized: suit components, emergency kit supplies, and the physical environment itself.\n\nKey protocol developments:\n\n1. Suit-as-splint protocol: EVA suit rigid components can immobilize extremity fractures for transport\n2. Towel protocol (Protocol T-1): The EVA emergency kit towel is classified as a legitimate multi-function medical device — tourniquet, sling, dust filter, pressure dressing, and, critically, privacy screen for examination (psychological importance in team settings)\n3. Remote cardiac assessment using suit telemetry\n4. Communication-first doctrine: establishing Earth telemedicine contact before any field procedure\n\nConclusion\n\nThe Botanical First Aid Framework provides a systematic approach to improvised EVA medical care. Implementation in all EVA training curricula is recommended.",
                'journal_name'     => 'Lunar Emergency Medicine',
                'volume_issue'     => 'Vol. 2, No. 1',
                'published_date'   => '2028-07-01',
                'keywords'         => 'EVA, field medicine, improvised, first aid, remote, protocol',
                'body_system'      => 'trauma',
                'featured'         => false,
            ],
            [
                'title'            => 'Room 42 Protocol: Psychosocial Support Framework for Serious Medical Diagnosis in Lunar Residents',
                'research_type'    => 'policy',
                'author_name'      => $authors[5]['name'],
                'author_affiliation' => $authors[5]['affiliation'],
                'abstract'         => 'Receiving a serious medical diagnosis — cancer, progressive SANS, significant bone loss — in a lunar habitat far from family creates unique psychological challenges. This paper describes the Room 42 Protocol, a structured psychosocial support framework developed at Mare Imbrium Medical Institute for supporting residents through serious diagnosis and treatment decisions.',
                'content'          => "The number 42 appears throughout human attempts to find meaning in uncertain and difficult circumstances. At Mare Imbrium Medical Institute, Room 42 is the private consultation room where medical officers deliver difficult diagnoses. The name was chosen deliberately: not as an answer, but as an acknowledgment that some questions have no simple answers — and that the dignity of the person in the room is the only constant.\n\nThe psychological context of a serious diagnosis in a lunar habitat is unlike any Earth equivalent. The resident cannot travel to be with family. Medical treatment may require evacuation — a dangerous journey that may itself be medically unwise. Communication with loved ones is asynchronous. The medical officer delivering the news is also the resident's daily colleague and often the only healthcare provider within 384,400 km.\n\nRoom 42 Protocol Steps:\n\n1. Advance preparation: Review patient's listed family contacts, communication preferences, and any advance care directives before the consultation.\n\n2. Environment: Private room, face-to-face, no interruptions. Offer communication link to family if available and patient wishes.\n\n3. Delivery framework: SPIKES protocol adapted for lunar context (Setting, Perception, Invitation, Knowledge, Emotions, Strategy).\n\n4. Information provision: Written summary provided. Earth specialist telemedicine consultation arranged within 48 hours.\n\n5. Ongoing support: Psychological counseling scheduled. Peer support contact offered. Habitat commander notified only with patient consent.\n\n6. Mission implications: Practical discussion of evacuation options, mission continuation possibilities, and patient autonomy in decisions.\n\nConclusion\n\nThe Room 42 Protocol provides a structured, humane framework for one of the most challenging aspects of lunar medical practice. We recommend adoption across all permanent lunar facilities.",
                'journal_name'     => 'Lunar Medical Ethics',
                'volume_issue'     => 'Vol. 1, No. 3',
                'published_date'   => '2028-05-10',
                'keywords'         => 'diagnosis, psychological support, communication, ethics, cancer, lunar medicine',
                'body_system'      => 'psychological',
                'featured'         => true,
            ],
            // ── Cardiovascular ────────────────────────────────────────────────────
            [
                'title'          => 'Cardiac Output Adaptation in Long-Duration Lunar Residents: Echocardiographic Findings at 6, 12, and 24 Months',
                'research_type'  => 'clinical_trial',
                'author_name'    => $authors[6]['name'],
                'author_affiliation' => $authors[6]['affiliation'],
                'abstract'       => 'Serial echocardiography in 83 lunar residents demonstrates progressive left ventricular mass reduction and decreased stroke volume over 24 months in 1/6g. Findings are consistent with reduced preload demand and have implications for exercise prescription and cardiovascular medication dosing.',
                'content'        => "Cardiac deconditioning in microgravity is well documented. The lunar partial-gravity environment attenuates but does not eliminate these changes. We enrolled 83 adult lunar residents aged 24–58 at Copernicus Station and performed echocardiography at baseline and 6-month intervals.\n\nKey findings: LV mass decreased by a mean of 6.4% at 12 months and 9.1% at 24 months. Stroke volume decreased proportionally. Resting cardiac output was maintained through compensatory heart rate increase. Exercise capacity declined in parallel with LV mass.\n\nCardiovascular medication dosing in lunar residents requires adjustment: beta-blockers reduce a heart rate that is already compensating for reduced stroke volume. We recommend conservative dose reductions of 20–30% for rate-limiting agents.\n\nThese findings reinforce the importance of the daily resistance exercise protocol and suggest that two-year assignments may represent the outer limit of uncomplicated cardiovascular health without pharmacological countermeasures.",
                'journal_name'   => 'Lunar Cardiology',
                'volume_issue'   => 'Vol. 2, No. 3',
                'published_date' => '2029-04-10',
                'keywords'       => 'cardiac, echocardiography, LV mass, deconditioning, stroke volume, lunar',
                'body_system'    => 'cardiovascular',
                'featured'       => false,
            ],
            // ── Musculoskeletal ───────────────────────────────────────────────────
            [
                'title'          => 'Fracture Mechanics in Low-Gravity: Biomechanical Analysis of 127 Lunar Mining Injuries',
                'research_type'  => 'case_study',
                'author_name'    => $authors[2]['name'],
                'author_affiliation' => $authors[2]['affiliation'],
                'abstract'       => 'Fracture patterns in lunar mining injuries differ systematically from Earth equivalents. Lower fall energy but higher equipment mass create distinct injury mechanics. We analyzed 127 fracture cases across five habitats to characterize patterns and inform protective equipment standards.',
                'content'        => "Mining operations on the lunar surface expose workers to a distinctive injury profile. The combination of lower gravitational acceleration (1.63 m/s²) and heavy equipment creates fall energies that, while lower than Earth equivalents, occur in an environment where bone density is often reduced by prior residency.\n\nOur retrospective analysis identified 127 fracture cases from five mining habitats over 48 months. Distal radius fractures were most common (31%), followed by metatarsal fractures from dropped equipment (24%), and rib fractures from equipment pinning (18%).\n\nA notable pattern: high-energy fractures (from equipment rather than falls) showed more severe comminution than Earth equivalents — the combination of equipment mass and even low-g impact energy exceeds bone strength in deconditioned residents.\n\nWe recommend BMD screening before assignment to mining operations and mandatory protective equipment standards for distal extremities.",
                'journal_name'   => 'Lunar Occupational Health',
                'volume_issue'   => 'Vol. 3, No. 1',
                'published_date' => '2029-11-05',
                'keywords'       => 'fracture, mining, trauma, low gravity, bone, injury prevention',
                'body_system'    => 'musculoskeletal',
                'featured'       => false,
            ],
            // ── Neurological ──────────────────────────────────────────────────────
            [
                'title'          => 'Space-Associated Neuro-Ocular Syndrome in Lunar Residents: Intracranial Pressure Dynamics in 1/6g',
                'research_type'  => 'clinical_trial',
                'author_name'    => $authors[0]['name'],
                'author_affiliation' => $authors[0]['affiliation'],
                'abstract'       => 'SANS presents in lunar residents at rates lower than microgravity but higher than Earth controls. The partial gravity attenuates cephalad fluid shift but does not eliminate it. MRI, OCT, and intracranial pressure monitoring data from 42 residents over 18 months characterize the lunar SANS phenotype.',
                'content'        => "Space-Associated Neuro-Ocular Syndrome (SANS) — the constellation of optic disc edema, choroidal folds, globe flattening, and hyperopic shift — has been a defining concern of long-duration spaceflight. In lunar partial gravity, the cephalad fluid shift that drives SANS is attenuated compared to microgravity.\n\nWe monitored 42 residents with serial MRI, optical coherence tomography (OCT), and lumbar puncture opening pressure. At 18 months, 14 residents (33%) showed mild optic disc changes. Three (7%) showed globe flattening on MRI. No resident developed the severe SANS phenotype seen in long-duration ISS astronauts.\n\nIntracranial pressure measurements showed mild elevation above Earth norms (mean 18.4 cmH₂O vs Earth normal <20), consistent with mild residual cephalad shift in 1/6g.\n\nConclusion: Lunar SANS is real but attenuated. Annual OCT screening and MRI at 12 months are recommended for all long-duration residents. Residents with ICP >22 cmH₂O should be considered for early evacuation evaluation.",
                'journal_name'   => 'Lunar Neurology',
                'volume_issue'   => 'Vol. 1, No. 4',
                'published_date' => '2028-12-01',
                'keywords'       => 'SANS, intracranial pressure, optic disc, ICP, neuro-ocular, MRI, OCT',
                'body_system'    => 'neurological',
                'featured'       => true,
            ],
            // ── Ophthalmological ─────────────────────────────────────────────────
            [
                'title'          => 'Vision Changes in Lunar Residents: A Prospective Registry of 214 Cases',
                'research_type'  => 'clinical_trial',
                'author_name'    => $authors[3]['name'],
                'author_affiliation' => $authors[3]['affiliation'],
                'abstract'       => 'Vision changes affect approximately 40% of lunar residents at 12 months, with hyperopic shift being most common. This registry documents the onset, progression, and partial reversibility of vision changes, and evaluates refractive correction strategies for the lunar environment.',
                'content'        => "Visual acuity changes are among the most commonly reported health concerns of lunar residents. Our prospective registry enrolled all residents at Tycho Base Medical Center from 2027–2030.\n\nAt 12 months, 41% of residents reported subjective vision change. Objective testing confirmed hyperopic shift >0.5 diopters in 38%. Mean hyperopic shift at 24 months was +0.8 diopters. Full reversal was observed in 60% of residents within 6 months of return to Earth.\n\nCorrection strategies evaluated: progressive lenses adjusted quarterly (most effective), over-the-counter readers (+1.0 to +2.5 D stocked in medical bays), and prophylactic acetazolamide (showed modest reduction in shift rate, not recommended as standard practice due to diuretic effects).\n\nWe recommend baseline ophthalmological assessment before lunar residency and standardized assessment at 6 and 12 months. Medical bays should stock a range of corrective lenses.",
                'journal_name'   => 'Lunar Ophthalmology Reports',
                'volume_issue'   => 'Vol. 2, No. 2',
                'published_date' => '2030-01-20',
                'keywords'       => 'vision, hyperopia, refractive shift, optic disc, eye health, lunar',
                'body_system'    => 'ophthalmological',
                'featured'       => false,
            ],
            // ── Oncological / Radiation ───────────────────────────────────────────
            [
                'title'          => 'The Golden Apples of the Sun: Radiation Dose Aesthetics and the Psychology of Dosimetry in Lunar Workers',
                'research_type'  => 'perspective',
                'author_name'    => $authors[7]['name'],
                'author_affiliation' => $authors[7]['affiliation'],
                'abstract'       => 'Radiation exposure is ubiquitous in the lunar environment, yet residents consistently underestimate their personal dose. This perspective examines the psychological barriers to dosimetry engagement — the quiet beauty of the radiation-hazard environment, its invisibility, and the human tendency to discount diffuse long-term risk — and proposes behavioral interventions.',
                'content'        => "There is a peculiar aesthetic quality to the lunar radiation environment. The surface is drenched in invisible energy — galactic cosmic rays, solar particle events, secondary neutrons from regolith interaction — that workers describe as feeling nothing, seeing nothing, yet knowing the exposure is accumulating. Poets have written about the sun's light as something to be approached with reverence and distance. Bradbury's image of reaching for 'the golden apples of the sun' captures the paradox: beautiful, sustaining, lethal in excess.\n\nDosimetry compliance in lunar workers averages 73% in our multi-habitat survey — a rate that would be unacceptable in any Earth nuclear facility. The causes are predictable: dosimeters are inconvenient, dose is invisible, consequences are decades away.\n\nWe propose a behavioral intervention framework drawing on behavioral economics: making dosimetry the default (automatic assignment), making exposure visible (real-time display on suit HUD), and making consequences proximate (personalized lifetime dose tracking with projected risk).\n\nThe goal is not to frighten — it is to make the invisible visible, and the distant near. Radiation management is the long game in lunar occupational health.",
                'journal_name'   => 'Lunar Radiation Medicine',
                'volume_issue'   => 'Vol. 1, No. 1',
                'published_date' => '2028-06-21',
                'keywords'       => 'radiation, dosimetry, psychology, GCR, solar particle event, cancer prevention, dose compliance',
                'body_system'    => 'oncological',
                'featured'       => true,
            ],
            // ── Infectious Disease ────────────────────────────────────────────────
            [
                'title'          => 'Antibiotic Stewardship in Closed Habitat Medicine: Preventing Resistance in a 50-Person Ecosystem',
                'research_type'  => 'policy',
                'author_name'    => $authors[1]['name'],
                'author_affiliation' => $authors[1]['affiliation'],
                'abstract'       => 'Antibiotic resistance emergence in a closed habitat poses existential risk to the community. With a finite formulary and no possibility of same-day resupply, a resistant organism could render an entire antibiotic class ineffective before replacement drugs arrive. We present antibiotic stewardship protocols adapted for the lunar closed-habitat context.',
                'content'        => "Standard antibiotic stewardship principles — reserve broad-spectrum agents, rotate classes, obtain cultures before treatment — apply with heightened urgency in a closed lunar habitat. A community of 50 people sharing air, water, and food represents an ideal ecosystem for resistance propagation.\n\nOur protocols were developed after a near-incident at Shackleton Base in 2027: a urinary tract infection treated empirically with ciprofloxacin was later found to involve a fluoroquinolone-resistant E. coli strain. Had this organism spread to the water supply, the habitat's entire fluoroquinolone supply would have been rendered ineffective.\n\nKey protocols: mandatory culture before empirical treatment when patient stability allows, 48-hour de-escalation review for all broad-spectrum agents, biannual resistance profiling of habitat microbiome, and strict antibiotic inventory reserve with Earth resupply triggers.\n\nThe philosophical framing matters: in a closed habitat, antibiotic prescribing is a community act, not just a clinical one. Every unnecessary broad-spectrum course degrades the shared therapeutic commons. TANSTAAFL applies — there ain't no such thing as a free antibiotic course.",
                'journal_name'   => 'Lunar Infectious Disease',
                'volume_issue'   => 'Vol. 2, No. 2',
                'published_date' => '2029-02-14',
                'keywords'       => 'antibiotics, stewardship, resistance, formulary, closed habitat, culture',
                'body_system'    => 'infectious',
                'featured'       => false,
            ],
            // ── Psychological ─────────────────────────────────────────────────────
            [
                'title'          => 'Grokking Pain: Mindfulness-Based Pain Management in Long-Duration Lunar Residents',
                'research_type'  => 'clinical_trial',
                'author_name'    => $authors[4]['name'],
                'author_affiliation' => $authors[4]['affiliation'],
                'abstract'       => 'Chronic musculoskeletal pain is the leading cause of prescription opioid use in lunar residents. Opioid supply constraints and addiction risk make pharmacological management untenable as a primary strategy. We evaluate a mindfulness-based pain management program adapted for the lunar environment, achieving 34% reduction in opioid consumption over 6 months.',
                'content'        => "The word 'grok' — coined by Heinlein to describe a total, empathetic understanding so complete that the understood and the understander merge — was adopted by a generation of pain psychologists to describe the goal of mindfulness-based pain management: not to eliminate pain, but to understand it so thoroughly, so non-judgmentally, that its power to cause suffering is transformed.\n\nIn a lunar habitat, the case for mindfulness is practical as well as philosophical. Opioid medications represent a finite, expensively resupplied resource. A resident who depends on opioids for chronic pain management becomes a supply chain vulnerability. Tolerance develops. And the psychological isolation of lunar habitation amplifies the addictive pull of any pharmacological relief.\n\nOur 6-month trial enrolled 28 residents with chronic musculoskeletal pain (primarily low back and knee). The intervention: daily 30-minute mindfulness sessions adapted for the habitat environment, with particular attention to the novel sensory experience of 1/6g embodiment as a focus object.\n\nResults: Opioid consumption decreased by 34% in the intervention group. Pain interference scores (BPI) decreased by 2.1 points (scale 0–10). Mood and sleep scores improved as secondary outcomes.\n\nGrokking pain — fully understanding it without resistance — is not a metaphor. It is a clinical technique with measurable outcomes.",
                'journal_name'   => 'Lunar Behavioral Medicine',
                'volume_issue'   => 'Vol. 2, No. 4',
                'published_date' => '2029-08-15',
                'keywords'       => 'mindfulness, chronic pain, opioids, CBT, musculoskeletal, psychological, pain management',
                'body_system'    => 'psychological',
                'featured'       => true,
            ],
            // ── Nutritional ───────────────────────────────────────────────────────
            [
                'title'          => 'Vitamin D Deficiency in Lunar Residents: Paradox, Mechanism, and Supplementation Protocol',
                'research_type'  => 'clinical_trial',
                'author_name'    => $authors[8]['name'],
                'author_affiliation' => $authors[8]['affiliation'],
                'abstract'       => 'Vitamin D deficiency is paradoxically prevalent in lunar residents despite the surface receiving unfiltered solar UV. The paradox resolves on examination: residents spend essentially no time on the surface without full-body EVA suits, and habitat UV lighting is calibrated for eye safety, not skin synthesis. We evaluate supplementation protocols against the lunar dietary baseline.',
                'content'        => "The Moon receives abundant unfiltered ultraviolet radiation. A lunar resident might reasonably expect no vitamin D deficiency. The paradox: residents spend an average of 4.2 EVA hours per week on the surface, in full suits that exclude all UV. The remaining 163+ hours per week are spent in habitats lit for eye safety at wavelengths that do not stimulate skin D synthesis.\n\nOur survey of 156 residents found 68% with serum 25-OH-D below 30 ng/mL (deficiency threshold), and 31% below 20 ng/mL (severe deficiency). The hydroponics-based diet provides essentially no dietary vitamin D.\n\nA three-arm supplementation trial (placebo, 2000 IU/day, 4000 IU/day) over 6 months showed that 4000 IU/day normalized serum levels in 94% of deficient residents.\n\nRecommendation: Universal vitamin D supplementation at 4000 IU/day for all lunar residents. This should be standard in the medical formulary, with serum monitoring every 6 months.",
                'journal_name'   => 'Lunar Nutrition and Metabolism',
                'volume_issue'   => 'Vol. 1, No. 2',
                'published_date' => '2028-09-30',
                'keywords'       => 'vitamin D, deficiency, supplementation, hydroponics, diet, bone health',
                'body_system'    => 'nutritional',
                'featured'       => false,
            ],
            // ── Respiratory ───────────────────────────────────────────────────────
            [
                'title'          => 'CO₂ Accumulation in Sleeping Quarters: A Silent Cognitive Hazard in Lunar Habitats',
                'research_type'  => 'equipment_review',
                'author_name'    => $authors[9]['name'],
                'author_affiliation' => $authors[9]['affiliation'],
                'abstract'       => 'CO₂ accumulation in closed sleeping quarters creates measurable cognitive impairment at levels that fall below symptom-awareness thresholds. Monitoring of 40 sleeping quarters across three habitats found that 22% regularly exceeded 2000 ppm during sleep. We describe a low-cost ventilation protocol that resolves the issue.',
                'content'        => "Carbon dioxide accumulation in small, poorly-ventilated sleeping quarters is a known but underappreciated hazard in submarine and spacecraft environments. In lunar habitats, the combination of smaller per-person air volumes and energy-conservation ventilation settings creates conditions for nightly CO₂ buildup.\n\nWe monitored CO₂ levels in 40 individual sleeping quarters across three habitats during normal sleep. Twenty-two percent regularly exceeded 2000 ppm — a level associated with measurable cognitive impairment on next-morning testing (Stroop interference task, working memory). Five quarters exceeded 3500 ppm, a level associated with significant physiological response.\n\nResidents were largely unaware: CO₂ exposure at these levels does not produce obvious symptoms until >5000 ppm. The effects are 'invisible' — next-morning cognitive dullness attributed to poor sleep quality, headache attributed to stress.\n\nThe fix is inexpensive: increasing sleeping quarter air exchange rate from 0.5 to 1.5 ACH reduced mean CO₂ to below 1000 ppm in all quarters. We recommend this as a mandatory standard in habitat design and remediation.",
                'journal_name'   => 'Lunar Environmental Medicine',
                'volume_issue'   => 'Vol. 1, No. 3',
                'published_date' => '2028-04-05',
                'keywords'       => 'CO2, carbon dioxide, ventilation, cognitive impairment, sleeping quarters, air quality',
                'body_system'    => 'respiratory',
                'featured'       => false,
            ],
            // ── Dermatological ────────────────────────────────────────────────────
            [
                'title'          => 'EVA Suit Dermatitis: Clinical Characterization and Mitigation Strategies in 89 Cases',
                'research_type'  => 'case_study',
                'author_name'    => $authors[10]['name'],
                'author_affiliation' => $authors[10]['affiliation'],
                'abstract'       => 'Prolonged contact with EVA suit materials causes a spectrum of dermatological conditions in frequent EVA workers. Pressure-point dermatitis, contact sensitization, and regolith particle microtrauma combine to create a distinct clinical syndrome. We describe 89 cases and evaluate mitigation strategies.',
                'content'        => "EVA suit dermatitis represents a convergence of physical and chemical irritant mechanisms. The inner suit liner creates prolonged pressure contact at predictable anatomical sites. Sweat and suit material interaction creates a chemical irritant environment. And despite decontamination protocols, microscopic regolith particles persist on suit inner surfaces and act as mechanical irritants.\n\nWe retrospectively analyzed 89 cases presenting to Aristarchus Plateau Clinic over 36 months. Pressure dermatitis at shoulder and hip contact points was most common (58%). Contact dermatitis from suit polymer off-gassing affected 21 cases. Regolith microtrauma presented as a distinctive distribution of folliculitis-like lesions in 10 cases.\n\nMitigation strategies with demonstrated efficacy: moisture-wicking liner replacements (reduces pressure dermatitis incidence by 40%), barrier cream applied at pressure points before EVA, and suit liner laundering every 3 EVA cycles rather than weekly.\n\nA practical note for field conditions: the multi-function EVA kit towel (standard issue) serves as an effective post-EVA skin assessment tool — residual regolith on skin is visible against white towel fibers and should prompt additional decontamination before suit removal.",
                'journal_name'   => 'Lunar Dermatology',
                'volume_issue'   => 'Vol. 1, No. 4',
                'published_date' => '2029-05-18',
                'keywords'       => 'dermatitis, EVA, suit, regolith, skin, contact dermatitis, occupational dermatology',
                'body_system'    => 'dermatological',
                'featured'       => false,
            ],
            // ── Trauma ───────────────────────────────────────────────────────────
            [
                'title'          => 'Haemostasis in Lunar Emergency Medicine: Blood Product Shelf Life and Trauma Protocol Adaptations',
                'research_type'  => 'equipment_review',
                'author_name'    => $authors[11]['name'],
                'author_affiliation' => $authors[11]['affiliation'],
                'abstract'       => 'Trauma management in lunar habitats is constrained by blood product shelf life and resupply intervals. This review evaluates blood product storage, tranexamic acid as a first-line agent, whole blood protocols, and the evidence base for damage control resuscitation adapted to remote austere environments.',
                'content'        => "Blood product availability is the most critical constraint in lunar trauma management. Packed red blood cells expire in 42 days. Fresh frozen plasma expires in 12 months (frozen) but must be thawed within 24 hours of use. Platelets expire in 5–7 days and cannot be practically maintained at a lunar habitat with current logistics.\n\nOur approach: tranexamic acid (TXA) 1g IV as universal early adjunct for suspected significant hemorrhage — a low-cost, long shelf-life intervention with strong evidence in damage control resuscitation. All lunar medical officers are trained in wilderness trauma protocols: wound packing, tourniquet application, and improvised pressure dressings from available materials.\n\nWhole blood from pre-typed walking blood bank donors — universal donors among the habitat population who have consented to emergency donation — provides a practical solution for acute hemorrhage in settings where component therapy is unavailable.\n\nThe mostly harmless injuries — minor lacerations, contusions, abrasions — require standard wound care. It is the rare major trauma case that tests the limits of lunar capability, and preparation for that case through protocols and training is the core of this work.",
                'journal_name'   => 'Lunar Emergency Medicine',
                'volume_issue'   => 'Vol. 3, No. 2',
                'published_date' => '2030-03-01',
                'keywords'       => 'trauma, haemostasis, blood products, TXA, tranexamic acid, damage control, emergency medicine',
                'body_system'    => 'trauma',
                'featured'       => false,
            ],
            // ── Multi-generational / Expanse-inspired ─────────────────────────────
            [
                'title'          => 'Multi-Generational Physiological Adaptation in Lunar-Born Residents: Preliminary Data from the First Cohort',
                'research_type'  => 'perspective',
                'author_name'    => $authors[5]['name'],
                'author_affiliation' => $authors[5]['affiliation'],
                'abstract'       => 'The first cohort of individuals born and raised in lunar partial gravity is approaching adulthood. Early data suggest physiological adaptations that differ systematically from Earth-born residents: taller mean height, reduced cardiovascular reserve, and altered bone geometry. The medical implications of a permanently gravity-adapted population are examined.',
                'content'        => "Science fiction anticipated this moment before medicine did. Writers imagining communities of the outer solar system described populations that had adapted over generations to low-gravity environments — taller, more fragile by Earth standards, physiologically distinct from their Earth-born ancestors. Culturally, such communities developed distinct identities shaped by their physical environment, with their own medical traditions, vocabulary, and relationship to the gravity-adaptation challenge.\n\nThe first 23 lunar-born individuals who have reached the age of 18 are now entering our medical registry. Early findings are striking. Mean height is 4.2 cm above Earth population norms adjusted for parental height. Femoral neck geometry shows reduced cortical thickness with compensatory increased diameter — an adaptation that maintains bending strength with less bone mass. Cardiovascular reserve on maximal exercise testing is reduced by approximately 15% compared to Earth-born peers.\n\nThe medical implications are significant. Should these individuals visit or relocate to Earth, the physiological stress would be substantial — likely exceeding the Earth-adaptation challenge faced by adult lunar residents, because the adaptation occurred during skeletal and cardiovascular development.\n\nWe recommend dedicated medical protocols for lunar-born individuals that differ from those designed for Earth-born residents on temporary lunar assignment. This population requires its own evidence base.",
                'journal_name'   => 'Journal of Lunar Medicine',
                'volume_issue'   => 'Vol. 4, No. 1',
                'published_date' => '2031-01-10',
                'keywords'       => 'multi-generational, lunar-born, adaptation, physiology, bone geometry, cardiovascular, height',
                'body_system'    => 'musculoskeletal',
                'featured'       => true,
            ],
            // ── Cooperative Governance / Heinlein ─────────────────────────────────
            [
                'title'          => 'The Harsh Mistress Wing: Cooperative Healthcare Governance Models for Self-Sustaining Lunar Communities',
                'research_type'  => 'policy',
                'author_name'    => $authors[6]['name'],
                'author_affiliation' => $authors[6]['affiliation'],
                'abstract'       => 'As lunar settlements mature from corporate outposts to self-governing communities, healthcare governance must evolve accordingly. This policy paper examines cooperative healthcare models — community-owned, collectively governed, pooling scarce medical resources across a settlement. The "Harsh Mistress Wing" governance framework, named for the cooperative organizational traditions of early lunar fiction, offers a model for resource-constrained medical commons.',
                'content'        => "The early lunar fiction tradition anticipated the governance challenge: a small, isolated community far from Earth authority, with acute resource constraints and high mutual interdependence, must develop its own governance structures. The insight that collective action and mutual accountability — not corporate hierarchy — would characterize successful lunar communities has proven prescient.\n\nThe Harsh Mistress Wing governance model, named in homage to that literary tradition, treats the medical commons as a collective resource. The medical cooperative is owned by habitat residents, governed by an elected council, and operates on the principle that healthcare costs are community costs. TANSTAAFL — there ain't no such thing as a free launch — applies: medical resources consumed today are unavailable for emergencies tomorrow. The council sets formulary priorities, approves non-emergency evacuations, and manages the medical budget as a shared ledger.\n\nThis model has been piloted at two independent lunar settlements. Results at 24 months: medication waste decreased by 31%, formulary coverage increased due to more efficient procurement, and resident satisfaction with healthcare governance scored higher than comparison corporate-model habitats.\n\nThe harsh mistress of scarcity, paradoxically, produces better collective outcomes when governance is structured to make that scarcity visible and shared.",
                'journal_name'   => 'Lunar Health Policy',
                'volume_issue'   => 'Vol. 1, No. 1',
                'published_date' => '2028-11-01',
                'keywords'       => 'governance, cooperative, healthcare policy, formulary, community medicine, resource allocation',
                'body_system'    => 'psychological',
                'featured'       => true,
            ],
            // ── Speculative / Revelation Space ────────────────────────────────────
            [
                'title'          => 'Bionanotechnology in Hostile Environments: Speculative Applications for Lunar Medicine in 2050',
                'research_type'  => 'perspective',
                'author_name'    => $authors[7]['name'],
                'author_affiliation' => $authors[7]['affiliation'],
                'abstract'       => 'Bionanotechnology — programmable molecular machines operating at the cellular level — represents the most transformative potential advance in lunar medicine. This speculative perspective examines applications including autonomous wound repair, real-time intracellular monitoring, targeted radiation damage correction, and adaptive drug delivery. The timeline for clinical implementation and the challenges specific to radiation-rich environments are discussed.',
                'content'        => "Medicine in hostile environments has always advanced on the frontier of technology. Speculative fiction has long imagined the endpoint: medical nanotechnology so advanced that the distinction between physician and pharmacist becomes irrelevant, where programmable molecular machines operate autonomously within the body, repairing damage, delivering drugs, and monitoring physiology at a resolution no macroscale instrument can match.\n\nFor lunar medicine, the applications are compelling. Radiation damage — the accumulating DNA strand breaks and oxidative injury from galactic cosmic ray exposure — is currently managed at the population level with dosimetry and early cancer screening. Molecular repair systems that could identify and correct radiation-induced DNA damage in real time would transform the risk calculus of long-duration lunar residency.\n\nWound healing in a vacuum-adjacent environment presents unique challenges that nanomedical systems could address: biofilm prevention in regolith-contaminated wounds, accelerated haemostasis in settings where blood product resupply is constrained, and real-time monitoring of wound microenvironment.\n\nThe timeline is speculative — most serious estimates place clinical-grade bionanotechnology 20–30 years from current capability. The radiation challenge is particularly difficult: the same cosmic ray flux that drives the need for nanotechnology also damages nanoscale electronic components. Biological and hybrid approaches show more promise than purely electronic ones.\n\nWe present this not as prediction but as orientation: the direction of travel for lunar medicine, and the research questions that current generations should be asking.",
                'journal_name'   => 'Lunar Medicine: Future Directions',
                'volume_issue'   => 'Vol. 1, No. 1',
                'published_date' => '2030-06-15',
                'keywords'       => 'nanotechnology, bionanotechnology, nanomedical, molecular machines, future medicine, speculative, radiation repair',
                'body_system'    => 'oncological',
                'featured'       => true,
            ],
            // ── Toxicological ─────────────────────────────────────────────────────
            [
                'title'          => 'Regolith Chemical Toxicity: Silicate Reactivity and Oxidative Stress Mechanisms in EVA Workers',
                'research_type'  => 'clinical_trial',
                'author_name'    => $authors[0]['name'],
                'author_affiliation' => $authors[0]['affiliation'],
                'abstract'       => 'Lunar regolith is chemically reactive in ways that distinguish it from terrestrial silicates. Unweathered silicates present high surface reactivity and generate reactive oxygen species on contact with biological tissue. We characterize the toxicity mechanisms and evaluate antioxidant prophylaxis in a controlled pilot study.',
                'content'        => "Terrestrial silicosis — pulmonary fibrosis from crystalline silica inhalation — provides an imperfect model for lunar regolith toxicity. Lunar regolith differs in critical ways: the absence of billions of years of weathering leaves the silicate surfaces chemically reactive, with dangling chemical bonds that generate reactive oxygen species (ROS) on contact with water, biological fluid, or tissue.\n\nThis ROS generation mechanism is likely the primary driver of the dose-response relationship we and others have documented between EVA exposure and respiratory inflammation markers.\n\nIn this 12-week pilot study, 30 EVA workers were randomized to N-acetylcysteine 600mg twice daily (antioxidant) or placebo. Exhaled breath condensate H₂O₂ (a marker of airway oxidative stress) decreased by 28% in the NAC group relative to placebo. Subjective respiratory symptoms also decreased.\n\nWe recommend a confirmatory trial with larger sample size and longer follow-up before NAC is added to standard EVA prophylaxis protocols. The biological plausibility is strong, and the intervention is low-cost and well-tolerated.",
                'journal_name'   => 'Lunar Toxicology',
                'volume_issue'   => 'Vol. 2, No. 1',
                'published_date' => '2029-01-25',
                'keywords'       => 'regolith, silicate, reactive oxygen species, oxidative stress, NAC, antioxidant, toxicology',
                'body_system'    => 'toxicological',
                'featured'       => false,
            ],
            // ── Circadian / Sleep ─────────────────────────────────────────────────
            [
                'title'          => 'Circadian Disruption in the 354-Hour Lunar Day: Sleep Architecture and Melatonin Dynamics',
                'research_type'  => 'clinical_trial',
                'author_name'    => $authors[9]['name'],
                'author_affiliation' => $authors[9]['affiliation'],
                'abstract'       => 'The lunar day (29.5 Earth days) imposes a light-dark cycle incompatible with human circadian biology. Habitat lighting protocols designed to simulate a 24-hour cycle are partially effective but incompletely prevent circadian disruption. Actigraphy, cortisol, and melatonin data from 38 residents characterize the lunar circadian phenotype.',
                'content'        => "The human circadian clock is calibrated to a 24-hour light-dark cycle through hundreds of thousands of years of evolution. In a lunar habitat, artificial lighting simulates this cycle with reasonable fidelity — but imperfectly. Light leakage through observation ports during the 14-day lunar 'day', habitat activity patterns, and irregular work schedules combine to create a challenging circadian environment.\n\nWe monitored 38 residents over 90 days using wrist actigraphy, morning cortisol, and midnight melatonin. Mean sleep efficiency was 78% (below the healthy adult norm of 85%). Melatonin onset was delayed by a mean of 47 minutes relative to pre-mission baseline. Cortisol awakening response was blunted, indicating disrupted hypothalamic-pituitary-adrenal axis function.\n\nInterventions with demonstrated benefit: blue light blocking glasses after 20:00 habitat time (melatonin onset normalized in 60% of users), structured 0.5mg melatonin supplementation 30 minutes before target sleep time, and strict light-dark habitat scheduling including blackout of common areas after 22:00.\n\nFor residents experiencing severe circadian disruption, short-term low-dose zolpidem or melatonin receptor agonists are appropriate adjuncts while behavioral interventions take effect.",
                'journal_name'   => 'Lunar Sleep Medicine',
                'volume_issue'   => 'Vol. 1, No. 2',
                'published_date' => '2028-02-10',
                'keywords'       => 'circadian, sleep, melatonin, cortisol, actigraphy, lunar day, insomnia, light',
                'body_system'    => 'neurological',
                'featured'       => false,
            ],
            // ── Emergency / Evacuation ────────────────────────────────────────────
            [
                'title'          => 'Evacuation Decision Criteria for Lunar Medical Emergencies: A Delphi Consensus from 47 Medical Officers',
                'research_type'  => 'policy',
                'author_name'    => $authors[11]['name'],
                'author_affiliation' => $authors[11]['affiliation'],
                'abstract'       => 'Medical evacuation from the Moon requires 3–5 days minimum and carries its own significant risk. The decision to evacuate is therefore more complex than in terrestrial medicine. This Delphi study recruited 47 experienced lunar medical officers to develop consensus criteria for evacuation vs. local management across 18 clinical scenarios.',
                'content'        => "The evacuation decision is the defining clinical judgment in lunar medicine. Unlike terrestrial emergency medicine — where transfer to a higher level of care is typically a risk-reduction move — lunar evacuation carries substantial risk: the patient must survive 72–120 hours of transit in a constrained vehicle, with limited monitoring and intervention capability, while physiologically stressed.\n\nThe decision criteria must therefore weigh the risk of the local condition (which may be severe) against the risk of evacuation (which is not trivial) and the probability that evacuation will reach Earth-level care before a critical deterioration.\n\nOur Delphi panel reached consensus on 18 clinical scenarios. Strong consensus (>80% agreement) for immediate evacuation: acute coronary syndrome unresponsive to initial management, progressive SANS with vision loss, hemorrhage beyond local haemostatic capability. Strong consensus for local management: appendicitis (laparoscopic capability now present at 4 habitats), uncomplicated fracture, mild-moderate SANS without vision involvement.\n\nThe most contentious scenarios were acute psychiatric crisis and end-stage cancer — where the calculus of patient autonomy, community resource consumption, and medical benefit is most complex.",
                'journal_name'   => 'Lunar Emergency Medicine',
                'volume_issue'   => 'Vol. 2, No. 3',
                'published_date' => '2029-07-22',
                'keywords'       => 'evacuation, emergency, decision criteria, Delphi, telemedicine, acute coronary syndrome',
                'body_system'    => 'trauma',
                'featured'       => true,
            ],
            // ── Telemedicine ──────────────────────────────────────────────────────
            [
                'title'          => 'Managing the 1.3-Second Delay: Telemedicine Protocol Optimization for Lunar Clinical Consultations',
                'research_type'  => 'equipment_review',
                'author_name'    => $authors[2]['name'],
                'author_affiliation' => $authors[2]['affiliation'],
                'abstract'       => 'Earth-Moon telemedicine consultations are complicated by a 2.6-second round-trip communication delay, making real-time dialogue cumbersome and procedure guidance impractical. This review evaluates structured consultation frameworks, asynchronous consultation models, and AI-assisted decision support designed for the lunar communication environment.',
                'content'        => "The 2.6-second round-trip delay between Earth and Moon (1.3 seconds each way at mean orbital distance) transforms telemedicine from a video call into something closer to a structured dialogue protocol. Natural conversation is difficult: both parties instinctively begin speaking during the pause, creating crosstalk.\n\nProtocol optimization has focused on three approaches: (1) Structured turn-taking with explicit end-of-transmission markers, adopted from radio communication protocols; (2) Pre-consultation data packages — the lunar medical officer uploads all relevant data (history, vitals, imaging, video) before the call, enabling the Earth specialist to arrive prepared; (3) Asynchronous store-and-forward consultation for non-urgent cases, eliminating the delay problem entirely.\n\nFor procedure guidance (endotracheal intubation, chest tube placement, emergency surgical procedures), the delay makes real-time instruction impractical. Our solution: pre-recorded procedure guidance libraries tailored to the lunar medical bay's specific equipment, supplemented by AI-assisted decision support with no communication latency.\n\nAt 1.3 seconds out and 1.3 seconds back, the lunar medical officer is ultimately on their own. The role of Earth telemedicine is preparation, not real-time rescue.",
                'journal_name'   => 'Lunar Telemedicine',
                'volume_issue'   => 'Vol. 2, No. 1',
                'published_date' => '2028-10-01',
                'keywords'       => 'telemedicine, communication delay, consultation, asynchronous, procedure guidance, AI decision support',
                'body_system'    => 'trauma',
                'featured'       => false,
            ],
            // ── Mostly Harmless ───────────────────────────────────────────────────
            [
                'title'          => 'Mostly Harmless: Benign Conditions Systematically Overdiagnosed in Anxious Lunar Residents',
                'research_type'  => 'perspective',
                'author_name'    => $authors[10]['name'],
                'author_affiliation' => $authors[10]['affiliation'],
                'abstract'       => 'In a medical environment characterized by heightened awareness and limited diagnostic capability, benign conditions are frequently over-investigated and over-treated. This perspective identifies the ten most overdiagnosed benign conditions in lunar residents, examines the psychological and systemic drivers of overdiagnosis, and proposes calibration strategies for lunar medical officers.',
                'content'        => "Every medical officer in a new and unfamiliar environment develops a diagnostic bias toward severity. When the environment is the Moon, where every symptom potentially carries additional meaning — could this headache be intracranial pressure? Is this fatigue cardiovascular deconditioning? — the bias toward over-investigation is understandable and frequently costly.\n\nThe phrase 'mostly harmless' has a long informal history in medicine as a colloquial shorthand for the benign end of the differential. The challenge is that in a closed habitat with a finite formulary and a medical officer who is also the patient's daily colleague, the mostly harmless diagnosis requires explicit, confident communication — and the courage to withhold investigation in the face of an anxious patient.\n\nThe ten most overdiagnosed benign conditions in our multi-habitat audit: (1) Functional dyspepsia attributed to radiation toxicity; (2) Tension headache attributed to CO₂ accumulation or SANS; (3) Adjustment disorder attributed to major depressive disorder; (4) Musculoskeletal back pain attributed to bone loss; (5) Vasovagal syncope attributed to orthostatic hypotension; (6) Mild exercise-induced hypertension attributed to cardiovascular deconditioning; (7) Viral URI attributed to the habitat microbiome 'going wrong'; (8) Acne attributed to EVA suit dermatitis; (9) Normal melatonin-induced morning grogginess attributed to CO₂ toxicity; (10) Anxiety-driven palpitations attributed to arrhythmia.\n\nCalibration requires explicit education of both residents (health literacy) and medical officers (cognitive bias awareness).",
                'journal_name'   => 'Lunar Medical Education',
                'volume_issue'   => 'Vol. 1, No. 2',
                'published_date' => '2028-07-20',
                'keywords'       => 'overdiagnosis, benign conditions, cognitive bias, health literacy, anxiety, differential diagnosis',
                'body_system'    => 'psychological',
                'featured'       => false,
            ],
        ];
    }

    private function generateVariants(array $base, int $count): array
    {
        $variants = [];
        $types = ['case_study', 'equipment_review', 'policy', 'clinical_trial', 'perspective', 'editorial'];
        $suffixes = [
            'An Update', 'A Multicenter Analysis', 'Preliminary Findings', 'Long-Term Follow-Up',
            'Systematic Review', 'A Case Series', 'Current Perspectives', 'Expert Consensus',
            'Retrospective Analysis', 'A Pilot Study', 'Population Analysis', 'Emerging Concepts',
        ];
        $bodies = [
            'cardiovascular', 'musculoskeletal', 'respiratory', 'neurological',
            'psychological', 'ophthalmological', 'oncological', 'trauma', 'nutritional',
        ];

        for ($i = 0; $i < $count; $i++) {
            $b = $base[$i % count($base)];
            $suffix = $suffixes[$i % count($suffixes)];
            $author = [
                'Dr. A. Okonkwo', 'Dr. L. Martinez', 'Dr. K. Patel', 'Dr. R. Novak',
                'Dr. S. Ibrahim', 'Dr. T. Nguyen', 'Dr. M. Bianchi', 'Dr. P. Kowalski',
            ][$i % 8];

            $variants[] = [
                'title'             => $b['title'] . ': ' . $suffix,
                'research_type'     => $types[$i % count($types)],
                'author_name'       => $author,
                'author_affiliation'=> 'Lunar Medical Research Cooperative',
                'abstract'          => "Follow-up investigation building on prior work. " . Str::limit($b['abstract'], 300),
                'content'           => "Extended analysis and updated findings. " . Str::limit($b['content'], 800),
                'journal_name'      => $b['journal_name'],
                'volume_issue'      => 'Vol. ' . (($i % 4) + 2) . ', No. ' . (($i % 4) + 1),
                'published_date'    => now()->subMonths($i % 36)->format('Y-m-d'),
                'keywords'          => $b['keywords'],
                'body_system'       => $bodies[$i % count($bodies)],
                'featured'          => false,
            ];
        }
        return $variants;
    }
}
