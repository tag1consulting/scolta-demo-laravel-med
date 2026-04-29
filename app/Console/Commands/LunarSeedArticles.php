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
            ['name' => 'Dr. Chen Wei', 'affiliation' => 'Lunar Medical Research Cooperative'],
            ['name' => 'Dr. Fatima Al-Rashid', 'affiliation' => 'Mare Imbrium Medical Institute'],
            ['name' => 'Dr. James Okafor', 'affiliation' => 'Shackleton Crater Health Sciences'],
            ['name' => 'Dr. Yuki Tanaka', 'affiliation' => 'Tycho Base Medical Center'],
            ['name' => 'Dr. Priya Sharma', 'affiliation' => 'Copernicus Station Clinic'],
            ['name' => 'Dr. Elena Volkova', 'affiliation' => 'International Lunar Medical Commission'],
            ['name' => 'Dr. Michael Oduya', 'affiliation' => 'Armstrong Settlement Health Services'],
            ['name' => 'Dr. Sarah Blackwood', 'affiliation' => 'Oceanus Procellarum Research Station'],
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
