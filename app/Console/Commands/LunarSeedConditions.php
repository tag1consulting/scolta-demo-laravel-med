<?php

namespace App\Console\Commands;

use App\Models\Condition;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class LunarSeedConditions extends Command
{
    protected $signature = 'lunar:seed-conditions {--truncate : Truncate existing data first}';
    protected $description = 'Seed ~5,000 medical conditions adapted for the lunar environment';

    public function handle(): int
    {
        if ($this->option('truncate')) {
            Condition::truncate();
            $this->info('Existing conditions truncated.');
        }

        $this->info('Seeding lunar medical conditions...');

        $systems = $this->getConditionsBySystem();
        $total = 0;

        foreach ($systems as $system => $conditions) {
            $bar = $this->output->createProgressBar(count($conditions));
            $this->line("\n<info>{$system}</info> (" . count($conditions) . ' conditions)');
            $bar->start();

            foreach ($conditions as $data) {
                $slug = Str::slug($data['lunar_variant_name'] ?? $data['name']);
                $counter = 1;
                $baseSlug = $slug;
                while (Condition::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $counter++;
                }

                Condition::create([
                    'name'                 => $data['name'],
                    'slug'                 => $slug,
                    'lunar_variant_name'   => $data['lunar_variant_name'] ?? null,
                    'icd10_code'           => $data['icd10_code'] ?? null,
                    'body_system'          => $system,
                    'severity'             => $data['severity'] ?? 'moderate',
                    'description'          => $data['description'],
                    'lunar_risk_factors'   => $data['lunar_risk_factors'] ?? null,
                    'symptoms'             => $data['symptoms'],
                    'lunar_symptoms'       => $data['lunar_symptoms'] ?? null,
                    'diagnosis'            => $data['diagnosis'],
                    'treatment'            => $data['treatment'],
                    'treatment_lunar'      => $data['treatment_lunar'] ?? null,
                    'evacuation_criteria'  => $data['evacuation_criteria'] ?? null,
                    'prevention'           => $data['prevention'] ?? null,
                    'is_emergency'         => $data['is_emergency'] ?? false,
                    'search_keywords'      => $data['search_keywords'] ?? null,
                ]);
                $total++;
                $bar->advance();
            }
            $bar->finish();
        }

        $this->newLine(2);
        $this->info("Seeded {$total} conditions.");
        return self::SUCCESS;
    }

    private function getConditionsBySystem(): array
    {
        return [
            'cardiovascular'  => $this->cardiovascularConditions(),
            'musculoskeletal' => $this->musculoskeletalConditions(),
            'respiratory'     => $this->respiratoryConditions(),
            'neurological'    => $this->neurologicalConditions(),
            'psychological'   => $this->psychologicalConditions(),
            'dermatological'  => $this->dermatologicalConditions(),
            'ophthalmological'=> $this->ophthalmologicalConditions(),
            'oncological'     => $this->oncologicalConditions(),
            'infectious'      => $this->infectiousConditions(),
            'trauma'          => $this->traumaConditions(),
            'toxicological'   => $this->toxicologicalConditions(),
            'nutritional'     => $this->nutritionalConditions(),
            'endocrine'       => $this->endocrineConditions(),
            'gastrointestinal'=> $this->gastrointestinalConditions(),
            'renal'           => $this->renalConditions(),
            'hematological'   => $this->hematologicalConditions(),
            'immunological'   => $this->immunologicalConditions(),
            'reproductive'    => $this->reproductiveConditions(),
        ];
    }

    private function cardiovascularConditions(): array
    {
        $base = [
            [
                'name' => 'Orthostatic Hypotension',
                'lunar_variant_name' => 'Lunar Orthostatic Hypotension',
                'icd10_code' => 'I95.1',
                'severity' => 'moderate',
                'description' => 'A sudden drop in blood pressure when standing, severely exacerbated by the cardiovascular deconditioning that occurs in low-gravity environments. In 1/6 gravity, blood pools less in the legs, causing the cardiovascular system to partially lose its Earth-adapted reflexes. On return from a lunar EVA or after prolonged rest, standing may cause dangerous blood pressure drops.',
                'lunar_risk_factors' => 'Prolonged microgravity exposure causes cephalad fluid shifts and reduced plasma volume. The cardiovascular system loses its Earth-adapted baroreceptor reflexes within weeks. Risk is highest on return to full lunar gravity after extended EVA in microgravity conditions, during illness, and in residents with more than 6 months habitation.',
                'symptoms' => 'Dizziness, lightheadedness, blurred vision, weakness, nausea, fainting (syncope) upon standing.',
                'lunar_symptoms' => 'In lunar gravity, symptoms are often milder than on Earth but persist longer. Greying of vision ("tunneling") is common. Some residents report chronic mild symptoms that become background noise — a dangerous normalization.',
                'diagnosis' => 'Postural blood pressure measurement: drop of ≥20 mmHg systolic or ≥10 mmHg diastolic within 3 minutes of standing. Tilt-table testing in the medical bay.',
                'treatment' => 'Hydration, compression garments, increased dietary salt, fludrocortisone if chronic.',
                'treatment_lunar' => 'First line: 500mL oral rehydration solution. Compression garments from suit components. Have patient sit or recline. If syncope occurs, elevate legs (requires strapping in 1/6 g). Fludrocortisone from lunar pharmacy if recurring. Telemedicine consult if not resolving within 30 minutes.',
                'evacuation_criteria' => 'Repeated syncopal episodes unresponsive to treatment. New-onset severe hypertension following hypotensive episode. Associated chest pain or arrhythmia.',
                'prevention' => 'Daily cardiovascular exercise (minimum 90 min/day on resistance treadmill), maintaining adequate hydration, gradual postural changes, compression garments during EVA.',
                'is_emergency' => false,
                'search_keywords' => 'blood pressure, fainting, dizzy, standing, low gravity, cardiovascular',
            ],
            [
                'name' => 'Cardiac Deconditioning',
                'lunar_variant_name' => 'Lunar Cardiac Atrophy Syndrome',
                'icd10_code' => 'I51.89',
                'severity' => 'severe',
                'description' => 'Progressive reduction in cardiac muscle mass, stroke volume, and aerobic capacity resulting from reduced gravitational load on the cardiovascular system. In lunar gravity, the heart does not need to pump against the same hydrostatic pressure gradient as on Earth. Without mitigation, significant cardiac atrophy can occur within months.',
                'lunar_risk_factors' => 'Reduced gravitational load decreases cardiac workload. Fluid redistribution reduces preload. Physical inactivity, even inadvertent, accelerates deconditioning. Maximum risk in first 6 months without exercise protocol adherence.',
                'symptoms' => 'Reduced exercise tolerance, dyspnea on exertion, fatigue, decreased maximum heart rate and VO2 max.',
                'lunar_symptoms' => 'Subtle at first — residents often attribute symptoms to habitat conditions. Monitoring via wearable cardiac sensors shows declining performance metrics before symptoms appear.',
                'diagnosis' => 'Serial VO2 max testing, echocardiogram via portable ultrasound, cardiac output measurement, exercise tolerance testing.',
                'treatment' => 'Structured aerobic and resistance exercise program, cardiac monitoring, possible pharmacological support.',
                'treatment_lunar' => 'Mandatory exercise protocol enforcement. Resistance treadmill with lower-body negative pressure (LBNP) suit. Target: maintain pre-mission baseline ±10% at 6-month review. Quarterly echocardiography. If >15% decline, mandatory Earth telemedicine cardiology consult.',
                'evacuation_criteria' => 'VO2 max decline >30% from baseline. New arrhythmia. Ejection fraction <45%. Symptomatic heart failure.',
                'prevention' => 'Mandatory 90-minute daily exercise protocol, LBNP suit use, cardiovascular monitoring every 3 months, nutrition optimization.',
                'is_emergency' => false,
                'search_keywords' => 'heart, exercise, fitness, weakness, aerobic, cardiac muscle',
            ],
            [
                'name' => 'Deep Vein Thrombosis',
                'lunar_variant_name' => 'Lunar DVT — Venous Stasis Thrombosis',
                'icd10_code' => 'I82.4',
                'severity' => 'severe',
                'description' => 'Blood clot formation in deep veins, most commonly in the legs. The risk profile differs significantly from Earth: lunar gravity still provides some venous return assistance, but reduced activity during EVA prep, long habitat rest periods, and dehydration create a prothrombotic environment. Virchow\'s triad applies with unique lunar modifications.',
                'lunar_risk_factors' => 'Prolonged EVA preparation periods with suit restriction of leg movement. Dehydration from suit sweating. Elevated red blood cell mass in some residents. Radiation-induced coagulation changes. Any lunar surgery requiring post-operative immobility.',
                'symptoms' => 'Calf pain, leg swelling, warmth, erythema. May be asymptomatic until pulmonary embolism.',
                'lunar_symptoms' => 'Swelling may appear different in lunar gravity — more diffuse, less dependent. D-dimer elevation detected on routine labs. Pain on dorsiflexion (Homan\'s sign) remains useful.',
                'diagnosis' => 'Compression ultrasound (priority — available in medical bay). D-dimer blood test. Clinical probability scoring (Well\'s criteria modified for lunar context).',
                'treatment' => 'Anticoagulation (heparin followed by warfarin or direct oral anticoagulants), elevation, compression.',
                'treatment_lunar' => 'Initiate low molecular weight heparin immediately (enoxaparin). Lunar pharmacy stocks: maintain 90-day supply. Portable ultrasound confirmation. Monitor INR if transitioning to warfarin. PE risk assessment — if high risk, consider prophylactic evacuation. All post-surgical patients: pneumatic compression devices and LMWH prophylaxis.',
                'evacuation_criteria' => 'Suspected pulmonary embolism. Massive proximal DVT. Contraindication to anticoagulation. Hemodynamic instability.',
                'prevention' => 'Ambulation every 2 hours during long EVA prep, hydration protocol, mechanical compression devices for surgery, LMWH for high-risk periods.',
                'is_emergency' => true,
                'search_keywords' => 'blood clot, leg pain, swelling, thrombosis, veins, DVT, pulmonary embolism',
            ],
            [
                'name' => 'Pulmonary Embolism',
                'lunar_variant_name' => 'Lunar Pulmonary Embolism',
                'icd10_code' => 'I26',
                'severity' => 'critical',
                'description' => 'Life-threatening blockage of pulmonary arteries by blood clot, usually originating from deep vein thrombosis. In a lunar habitat, the limited diagnostic and treatment capabilities make this one of the most feared emergencies. Mortality without immediate treatment approaches 30% for massive PE.',
                'lunar_risk_factors' => 'All DVT risk factors. Radiation-induced coagulation abnormalities. Limited access to advanced interventional cardiology. 1.3-second communications delay prevents real-time telemedicine guidance during resuscitation.',
                'symptoms' => 'Sudden severe dyspnea, pleuritic chest pain, hemoptysis, syncope, cardiovascular collapse.',
                'lunar_symptoms' => 'Oxygen saturation drop on continuous monitoring may be the first sign. Tachycardia and hypotension. Right heart strain visible on portable ECG.',
                'diagnosis' => 'Clinical: Wells score + oxygen saturation monitoring + ECG. CT pulmonary angiography not available — rely on clinical diagnosis plus portable ultrasound (right heart strain, McConnell\'s sign).',
                'treatment' => 'Anticoagulation, hemodynamic support, systemic thrombolysis for massive PE.',
                'treatment_lunar' => 'PRIORITY ALPHA — activate emergency protocol. Administer O2, unfractionated heparin IV bolus (80 units/kg). If massive PE with cardiovascular collapse: systemic thrombolysis with alteplase (only if trained). Prepare for potential CPR (lunar gravity affects technique — adjust compression ratio). Immediate evacuation contact. Earth telemedicine guidance critical.',
                'evacuation_criteria' => 'All suspected pulmonary emboli warrant evacuation consideration. Massive PE requires immediate evacuation if patient stable enough for transport.',
                'prevention' => 'Aggressive DVT prevention. Maintain anticoagulation stocks. Yearly thrombophilia screening for high-risk residents.',
                'is_emergency' => true,
                'search_keywords' => 'chest pain, breathing difficulty, blood clot, lung, emergency, collapse',
            ],
            [
                'name' => 'Hypertension',
                'lunar_variant_name' => 'Lunar Hypertension',
                'icd10_code' => 'I10',
                'severity' => 'moderate',
                'description' => 'Persistently elevated blood pressure. The lunar environment creates paradoxical effects: initial hypotension from fluid redistribution often gives way to hypertension in established residents, driven by stress, sleep disruption, altered sodium regulation, and potentially radiation effects on the renin-angiotensin system.',
                'lunar_risk_factors' => 'Chronic stress (isolation, confinement, occupational hazards). Sleep disruption from 14-day light cycles. High-sodium preserved foods in lunar diet. Radiation effects on renal function. Stimulant medication use.',
                'symptoms' => 'Usually asymptomatic. Headache, visual changes, nose bleeds in hypertensive urgency/emergency.',
                'lunar_symptoms' => 'Headache in 1/6 gravity may also reflect intracranial pressure changes — distinguish carefully. Routine monitoring critical since symptoms are unreliable.',
                'diagnosis' => 'Automated blood pressure monitoring. Target: <130/80 mmHg. Ambulatory BP monitoring over 24 hours to assess true baseline.',
                'treatment' => 'Lifestyle modification, ACE inhibitors, ARBs, calcium channel blockers, diuretics.',
                'treatment_lunar' => 'First line: lifestyle modification (exercise, sodium restriction, stress reduction). Pharmacological: amlodipine (calcium channel blocker) preferred — good stability in lunar storage conditions. ACE inhibitors (lisinopril) second line. Avoid beta-blockers as first line (may worsen exercise deconditioning). Monthly BP monitoring minimum.',
                'evacuation_criteria' => 'Hypertensive emergency (>180/120 with end-organ damage). Uncontrolled hypertension with new neurological symptoms (rule out intracranial hemorrhage).',
                'prevention' => 'Sodium-restricted diet, regular cardiovascular exercise, stress management, adequate sleep, routine monitoring.',
                'is_emergency' => false,
                'search_keywords' => 'high blood pressure, headache, hypertension, cardiovascular, blood pressure',
            ],
            [
                'name' => 'Arrhythmia',
                'lunar_variant_name' => 'Lunar Cardiac Arrhythmia',
                'icd10_code' => 'I49.9',
                'severity' => 'moderate',
                'description' => 'Abnormal heart rhythms arising from electrolyte imbalance, radiation effects on cardiac conducting tissue, dehydration, stimulant use, or underlying cardiac deconditioning. Heavy particle radiation from galactic cosmic rays and solar particle events can cause transient ion channel disruption.',
                'lunar_risk_factors' => 'Cosmic radiation affecting cardiac ion channels. Electrolyte imbalances from dehydration and lunar diet. Cardiovascular deconditioning. Stimulant medication use for shift work. Stress and sleep deprivation.',
                'symptoms' => 'Palpitations, irregular heartbeat, lightheadedness, presyncope, chest pain, dyspnea.',
                'lunar_symptoms' => 'During solar particle events, multiple residents may report palpitations simultaneously — distinguish radiation-induced from individual pathology. Wearable cardiac monitors detect arrhythmias early.',
                'diagnosis' => '12-lead ECG, continuous Holter monitoring, electrolytes, thyroid function.',
                'treatment' => 'Correct underlying cause, anti-arrhythmic medication, cardioversion if unstable.',
                'treatment_lunar' => 'Electrolyte repletion (magnesium, potassium). Identify precipitant. For SVT: vagal maneuvers then adenosine (stock in crash kit). For AF: rate control with metoprolol, anticoagulation. For VT/VF: defibrillation — AED mandatory in all habitats. Telemedicine cardiology consult for new or persistent arrhythmia.',
                'evacuation_criteria' => 'Ventricular fibrillation post-resuscitation. New sustained ventricular tachycardia. Third-degree heart block. Arrhythmia causing hemodynamic instability.',
                'prevention' => 'Electrolyte monitoring, hydration, radiation monitoring during SPE, wearable cardiac monitoring for high-risk residents.',
                'is_emergency' => true,
                'search_keywords' => 'heart rate, palpitations, irregular heartbeat, rhythm, arrhythmia, radiation',
            ],
        ];

        // Generate additional variations to expand the dataset
        return array_merge($base, $this->generateVariants($base, 'cardiovascular', 80));
    }

    private function musculoskeletalConditions(): array
    {
        $base = [
            [
                'name' => 'Osteoporosis',
                'lunar_variant_name' => 'Lunar Accelerated Bone Loss',
                'icd10_code' => 'M81.0',
                'severity' => 'severe',
                'description' => 'Accelerated reduction in bone mineral density caused by reduced gravitational loading. On Earth, osteoporosis develops over decades; in lunar gravity, residents can lose 1-2% bone density per month in weight-bearing bones without countermeasures. Long-term residents risk spontaneous fractures from activities that would be routine on Earth.',
                'lunar_risk_factors' => 'Reduced gravitational loading is the primary driver — bones adapt to the lower force requirements. Vitamin D synthesis is impaired (no UV exposure in habitats). Calcium metabolism alterations. Physical inactivity. Limited dietary calcium if hydroponics supply is disrupted.',
                'symptoms' => 'Often asymptomatic until fracture. Back pain from vertebral compression. Height loss. Fragility fractures from minor trauma.',
                'lunar_symptoms' => 'Regular DXA scanning reveals bone loss before symptoms. First symptomatic fractures often occur during physical work in mining, construction, or even vigorous exercise.',
                'diagnosis' => 'DXA (dual-energy X-ray absorptiometry) — portable units available in medical bay. FRAX score adapted for lunar context. Serum calcium, phosphate, vitamin D, PTH.',
                'treatment' => 'Bisphosphonates, resistance exercise, calcium and vitamin D supplementation.',
                'treatment_lunar' => 'Mandatory resistance exercise protocol (minimum 90 min/day). Bisphosphonate therapy (zoledronic acid annually or weekly alendronate) for residents with >3% loss in any 6-month period. Vitamin D supplementation 2000-4000 IU daily. Calcium 1500 mg/day. Protein intake optimization. Quarterly DXA monitoring. Earth telemedicine endocrinology if not responding to first-line therapy.',
                'evacuation_criteria' => 'Vertebral compression fracture causing neurological compromise. Bone density T-score <-3.5 with ongoing loss despite maximum therapy. Hip fracture (consider surgical repair capacity before evacuation).',
                'prevention' => 'Mandatory exercise protocol before lunar deployment. Baseline DXA. Resistance exercise with loaded suits. Bisphosphonate prophylaxis for missions >12 months. Nutrition protocol enforcement.',
                'is_emergency' => false,
                'search_keywords' => 'bone loss, fracture, osteoporosis, density, calcium, vitamin D, bones',
            ],
            [
                'name' => 'Muscle Atrophy',
                'lunar_variant_name' => 'Lunar Myopathy Syndrome',
                'icd10_code' => 'M62.50',
                'severity' => 'moderate',
                'description' => 'Rapid loss of skeletal muscle mass and strength from reduced gravitational loading. In lunar gravity, muscles — especially postural muscles in the lower extremities and trunk — receive dramatically reduced stimulation. Without aggressive countermeasures, residents can lose significant muscle mass within weeks of arrival.',
                'lunar_risk_factors' => 'Reduced mechanical loading of anti-gravity muscles. Caloric deficit from early lunar habitation. Protein synthesis dysregulation. Psychological stress suppressing anabolic hormones. Immobility during illness or injury.',
                'symptoms' => 'Progressive weakness, exercise intolerance, difficulty with Earth return acclimation, increased injury risk.',
                'lunar_symptoms' => 'Residents may not notice gradual weakness until performing standardized strength tests. Performance on resistance exercises declines. Activities requiring Earth-normal strength (emergency evacuation procedures) become impaired.',
                'diagnosis' => 'Serial grip strength testing, 1-rep maximum testing, DEXA lean body mass, functional movement screening.',
                'treatment' => 'Resistance exercise program, protein supplementation, possible testosterone therapy in deficient males.',
                'treatment_lunar' => 'Mandatory resistance training protocol. Target: maintain >90% of pre-mission lean mass. Protein intake 1.6-2.2 g/kg/day from hydroponics sources. Creatine supplementation (5g/day). Blood Flow Restriction training if injury limits load. Monthly lean mass assessment. Nutritional consultation if not responding.',
                'evacuation_criteria' => 'Severe myopathy preventing emergency procedure execution. Unable to don/doff EVA suit independently. Rhabdomyolysis.',
                'prevention' => 'Pre-mission resistance training baseline. Mandatory exercise protocol. Protein targets. Regular functional assessment.',
                'is_emergency' => false,
                'search_keywords' => 'muscle weakness, atrophy, strength loss, exercise, low gravity effects',
            ],
            [
                'name' => 'Stress Fracture',
                'lunar_variant_name' => 'Low-G Stress Fracture',
                'icd10_code' => 'M84.35',
                'severity' => 'moderate',
                'description' => 'Bone fatigue fractures paradoxically occurring despite reduced gravitational loading — caused by the combination of osteopenic bones and the unique biomechanical stresses of low-gravity locomotion (bounding, hopping gait) that concentrates force differently than normal walking.',
                'lunar_risk_factors' => 'Osteopenia from chronic low-g exposure. Novel locomotion patterns (bounding gait). Mining and construction vibration. Suit-induced altered biomechanics. Return-to-exercise after periods of illness-related rest.',
                'symptoms' => 'Localized bone pain that worsens with activity and improves with rest. Swelling, point tenderness.',
                'lunar_symptoms' => 'Pain during bounding locomotion or EVA. Common sites differ from Earth: metatarsal and tibial stress fractures remain common; lumbar spine stress fractures more frequent due to altered loading.',
                'diagnosis' => 'Clinical suspicion high in affected resident. X-ray (portable unit) often negative initially. MRI if available. Ultrasound can detect periosteal reaction.',
                'treatment' => 'Activity modification, protected weight-bearing, possible immobilization, gradual return to activity.',
                'treatment_lunar' => 'Remove from EVA duty. Activity modification. Low-gravity orthopedic principles: gravity-assist walking using handrails. Moonboot/controlled ankle walking device from medical supplies. Bisphosphonate if underlying osteopenia. 6-week healing protocol with gradual return. Nutritional support.',
                'evacuation_criteria' => 'Complete fracture (displaced). Stress fracture at fracture-prone site (femoral neck) with ongoing osteopenia. Unable to perform mandatory emergency duties.',
                'prevention' => 'Gradual introduction to lunar locomotion. Proper footwear. Osteoporosis prevention. Avoid repetitive bounding gait patterns.',
                'is_emergency' => false,
                'search_keywords' => 'bone fracture, foot pain, leg pain, stress fracture, EVA, bounding',
            ],
            [
                'name' => 'Intervertebral Disc Herniation',
                'lunar_variant_name' => 'Lunar Disc Syndrome',
                'icd10_code' => 'M51.1',
                'severity' => 'moderate',
                'description' => 'Herniation of intervertebral disc material, causing nerve root compression. In lunar gravity, discs undergo paradoxical changes: they can expand slightly from reduced compressive loading (causing back pain and height increase in early residence) and then become more vulnerable to herniation during the biomechanically demanding tasks of lunar work.',
                'lunar_risk_factors' => 'Heavy EVA suit donning/doffing (repeated awkward lifting). Mining operations with heavy equipment. Disc expansion in early lunar residence increases vulnerability. Dehydration reducing disc resilience.',
                'symptoms' => 'Back or neck pain, radicular pain radiating to extremities, numbness, weakness, bowel/bladder dysfunction in severe cases.',
                'lunar_symptoms' => 'Height increase in first weeks of lunar residence from disc expansion — not always symptomatic but indicates disc vulnerability. Pain during EVA suit donning is common chief complaint.',
                'diagnosis' => 'Clinical neurological examination. MRI (if available). X-ray to rule out fracture. Straight leg raise test.',
                'treatment' => 'Physical therapy, pain management, epidural steroid injection, surgical discectomy if severe.',
                'treatment_lunar' => 'Pain management: NSAIDs, acetaminophen, muscle relaxants from pharmacy. Relative activity modification. Physical therapy program (specific exercises for low-g environment). Ice/heat (adapt for habitat conditions). Epidural steroid injection (trained personnel only). Surgical discectomy not available — evacuation if progressive neurological deficit.',
                'evacuation_criteria' => 'Cauda equina syndrome (bowel/bladder dysfunction). Progressive neurological deficit. Intractable pain unresponsive to maximum medical therapy. Inability to safely perform EVA duties.',
                'prevention' => 'Proper lifting technique for EVA suit. Ergonomic workstation design. Core strengthening exercises. Height monitoring in first month.',
                'is_emergency' => false,
                'search_keywords' => 'back pain, disc, herniation, nerve pain, sciatica, spine',
            ],
        ];

        return array_merge($base, $this->generateVariants($base, 'musculoskeletal', 90));
    }

    private function respiratoryConditions(): array
    {
        $base = [
            [
                'name' => 'Lunar Regolith Pneumoconiosis',
                'lunar_variant_name' => 'Lunar Dust Lung Disease',
                'icd10_code' => 'J62.8',
                'severity' => 'severe',
                'description' => 'Occupational lung disease from chronic inhalation of lunar regolith particles. Lunar dust is uniquely hazardous: particles are jagged and glassy (not smoothed by wind and water like Earth dust), highly reactive due to absence of oxidation weathering, and include sharp silicate shards at respirable particle sizes. This is potentially the most serious long-term occupational health risk on the Moon.',
                'lunar_risk_factors' => 'EVA operations in regolith-heavy areas. Inadequate airlock decontamination. Suit breach (rare). Habitat air filtration system failure. Mining operations with powered excavation. Construction generating dust clouds.',
                'symptoms' => 'Initially asymptomatic. Progressive: chronic cough, dyspnea on exertion, reduced lung function. Late stage: fibrosis, respiratory failure.',
                'lunar_symptoms' => 'Most insidious of all lunar occupational diseases — damage accumulates silently. First indication is often declining pulmonary function on annual spirometry. Residents with highest EVA hours are highest risk.',
                'diagnosis' => 'Chest X-ray (portable), pulmonary function testing (spirometry), high-resolution CT if available on evacuation. Bronchoalveolar lavage for particle confirmation.',
                'treatment' => 'No curative treatment. Remove from exposure, supportive care, corticosteroids for inflammatory phase.',
                'treatment_lunar' => 'Immediate removal from dust exposure. Corticosteroids (methylprednisolone) for acute inflammatory phase. Bronchodilators for airflow obstruction. N-acetylcysteine as antioxidant support. Pulmonary rehabilitation exercises. Report to habitat medical officer and Earth occupational health team. Permanent EVA restrictions if fibrosis confirmed.',
                'evacuation_criteria' => 'Spirometry showing FVC <60% predicted. Acute respiratory failure from dust exposure event. Progressive fibrosis despite treatment. FEV1/FVC ratio <0.7 with symptoms.',
                'prevention' => 'CRITICAL PREVENTION PRIORITY. Rigorous airlock decontamination protocol after every EVA. Personal respiratory protection. Regular spirometry (every 6 months for EVA workers). EVA hour limits. Air quality monitoring in habitats.',
                'is_emergency' => false,
                'search_keywords' => 'dust, lung, breathing, respiratory, regolith, silicosis, EVA',
            ],
            [
                'name' => 'Oxygen Toxicity',
                'lunar_variant_name' => 'Hyperoxic Pulmonary Syndrome',
                'icd10_code' => 'J68.3',
                'severity' => 'severe',
                'description' => 'Lung and CNS damage from elevated oxygen partial pressure. Lunar habitats may run on slightly enriched oxygen atmospheres; EVA suits use pure or near-pure oxygen at reduced pressure. Extended pure oxygen breathing can cause pulmonary oxygen toxicity, while high-pressure oxygen causes CNS toxicity.',
                'lunar_risk_factors' => 'Extended EVA operations in pure oxygen suit atmosphere. Habitat oxygen enrichment above 30% for fire safety reasons. Oxygen concentrator malfunction creating hyperoxygenated zones. Therapeutic oxygen delivery errors.',
                'symptoms' => 'Pulmonary: chest tightness, cough, dyspnea. CNS: visual disturbances, nausea, twitching, seizures (in hyperbaric conditions).',
                'lunar_symptoms' => 'Pulmonary form most common in lunar context (EVA suit use). Progressive dyspnea after long EVA. CNS form rare except in pressurized habitats with oxygen enrichment.',
                'diagnosis' => 'Clinical diagnosis based on oxygen exposure history. Chest X-ray shows diffuse infiltrates. PaO2 and SpO2 monitoring.',
                'treatment' => 'Reduce FiO2 immediately. Supportive care. Corticosteroids may help pulmonary form.',
                'treatment_lunar' => 'Immediately reduce oxygen to normal habitat air. Remove from enriched oxygen environment. If severe: oxygen titration to SpO2 >92% (not higher). Corticosteroids for pulmonary inflammation. Monitor SpO2 continuously. Earth telemedicine pulmonology consult.',
                'evacuation_criteria' => 'Severe respiratory failure requiring ventilatory support. Progressive fibrosis. Inability to tolerate any oxygen reduction.',
                'prevention' => 'Strict oxygen atmosphere protocols. Regular monitoring of habitat O2 levels. EVA time limits in pure O2 suits. Training on oxygen toxicity recognition.',
                'is_emergency' => true,
                'search_keywords' => 'oxygen, breathing, toxic, EVA suit, respiratory failure',
            ],
            [
                'name' => 'Hypercapnia',
                'lunar_variant_name' => 'CO2 Buildup Syndrome',
                'icd10_code' => 'J96.09',
                'severity' => 'critical',
                'description' => 'Dangerous elevation of carbon dioxide in blood, caused by CO2 scrubber failure, EVA suit malfunction, or habitat ventilation problems. CO2 is odorless and colorless; residents may not notice until incapacitated. This is one of the most acute life threats in a sealed lunar habitat or EVA suit.',
                'lunar_risk_factors' => 'LiOH/CO2 scrubber failure or depletion in EVA suit. Habitat ventilation system malfunction. Confined spaces with multiple residents and inadequate air circulation. Power failure affecting air management systems.',
                'symptoms' => 'Headache (often the first symptom), confusion, dyspnea, flushing, hypertension, tachycardia, loss of consciousness, cardiac arrhythmia.',
                'lunar_symptoms' => 'Collective onset affecting multiple residents simultaneously is the key diagnostic clue — indicates habitat CO2 buildup rather than individual medical problem. EVA CO2 toxicity affects suit occupant first.',
                'diagnosis' => 'CO2 alarm (habitat monitors). Blood gas if available. Clinical: sudden headache in EVA context is CO2 until proven otherwise. Check suit telemetry for CO2 partial pressure.',
                'treatment' => 'Immediate fresh air/reduced CO2 environment, supportive care, possible intubation in severe cases.',
                'treatment_lunar' => 'IMMEDIATE: Evacuate to fresh air zone or open fresh O2 supply. Remove EVA helmet if safe to do so. If suit, switch to backup O2. 100% oxygen by mask to displace CO2. For severe cases: assisted ventilation. Replace scrubber immediately. CO2 monitor to ensure habitat levels returning to normal. Telemedicine if >5 residents affected (may indicate systems failure).',
                'evacuation_criteria' => 'Loss of consciousness. Cardiac arrhythmia. Persistent neurological deficit. CO2 source cannot be corrected (evacuation mandatory).',
                'prevention' => 'Regular scrubber maintenance and replacement schedule. CO2 monitoring in all inhabited areas with audible alarms. Backup scrubber supplies. EVA suit CO2 monitoring with abort criteria.',
                'is_emergency' => true,
                'search_keywords' => 'CO2, carbon dioxide, headache, confusion, breathing, scrubber, toxic',
            ],
        ];

        return array_merge($base, $this->generateVariants($base, 'respiratory', 70));
    }

    private function neurologicalConditions(): array
    {
        $base = [
            [
                'name' => 'Vestibular Dysfunction',
                'lunar_variant_name' => 'Lunar Adaptation Vestibular Syndrome',
                'icd10_code' => 'H81.90',
                'severity' => 'moderate',
                'description' => 'Disruption of the vestibular system\'s ability to accurately interpret spatial orientation in the novel gravitational environment. The inner ear evolved for Earth\'s 1g gravity; in lunar 1/6g, the otolith organs (which detect linear acceleration and gravity) are chronically understimulated, leading to adaptation responses that create disorientation, vertigo, and spatial confusion.',
                'lunar_risk_factors' => 'Arrival in lunar gravity (acute adaptation). Return from Earth (re-adaptation). Extended EVA in microgravity above lunar surface. Sleep deprivation exacerbating vestibular compensation. Age >50 (reduced vestibular plasticity).',
                'symptoms' => 'Vertigo, dizziness, nausea, vomiting, spatial disorientation, difficulty with head movements.',
                'lunar_symptoms' => 'Most severe in first 3-5 days of lunar arrival. Bounding locomotion is particularly challenging — head movements during jumping cause spatial confusion. Night-time orientation in 1/6g requires habituation. Some residents develop "lunar vertigo" — chronic mild disorientation.',
                'diagnosis' => 'Clinical: Dix-Hallpike maneuver (modified for lunar gravity). Romberg test in lunar gravity conditions. VEMP testing if available. Otolith function assessment.',
                'treatment' => 'Vestibular rehabilitation exercises, anti-emetics for acute phase, habituation.',
                'treatment_lunar' => 'Acute phase: meclizine (antihistamine), promethazine if severe. Activity modification in first 48 hours. Structured vestibular rehabilitation program specific to lunar gravity (habituation exercises in 1/6g). Restrict EVA in first 10 days unless essential. Progressive exposure to challenging head movements. Most residents adapt within 2-3 weeks.',
                'evacuation_criteria' => 'Severe prolonged vertigo >2 weeks without improvement. CNS lesion causing secondary vestibular dysfunction (stroke, tumor). Vestibular dysfunction preventing critical duty performance.',
                'prevention' => 'Pre-mission vestibular training, gradual acclimatization protocol, vestibular exercises in first week.',
                'is_emergency' => false,
                'search_keywords' => 'dizzy, vertigo, balance, disorientation, inner ear, vestibular, space sickness',
            ],
            [
                'name' => 'Intracranial Hypertension',
                'lunar_variant_name' => 'Spaceflight-Associated Neuro-Ocular Syndrome',
                'icd10_code' => 'G93.2',
                'severity' => 'severe',
                'description' => 'Elevated intracranial pressure (ICP) associated with fluid redistribution in low gravity. In Earth gravity, blood and CSF are distributed by the hydrostatic pressure gradient. In reduced gravity, cephalad fluid shift occurs — more fluid accumulates in the head, potentially raising intracranial pressure. This syndrome has been extensively documented in spaceflight and is one of the most concerning long-term health risks for lunar residents.',
                'lunar_risk_factors' => 'Duration of lunar residence (longer = higher cumulative risk). Pre-existing venous anatomy variations. High-intensity exercise promoting cephalad fluid shift. Certain medications. Possible genetic susceptibility.',
                'symptoms' => 'Headache (especially on waking), visual disturbances, papilledema, pulsatile tinnitus, vision loss.',
                'lunar_symptoms' => 'Often subtle — residents may not report symptoms that they attribute to normal lunar adjustment. Regular ophthalmology screening (portable fundoscopy) detects papilledema before vision loss. Visual field changes are insidious.',
                'diagnosis' => 'Fundoscopy for papilledema. OCT (optical coherence tomography) for RNFL thickness. MRI for pituitary flattening, optic nerve sheath distension. Lumbar puncture for opening pressure if MRI not available.',
                'treatment' => 'Acetazolamide, topiramate, CSF diversion (not typically available in lunar medical bay), positional modification.',
                'treatment_lunar' => 'Acetazolamide (500 mg BID) as first-line ICP reduction. Sleep in 10-15° head elevation. Reduce dietary sodium. Treat any contributing factors. Ophthalmology follow-up every 3 months for affected residents. If visual field loss: urgent Earth telemedicine neurology/ophthalmology. Possible evacuation for advanced cases.',
                'evacuation_criteria' => 'Progressive visual field loss. Visual acuity decline. Papilledema grade 4+. Opening pressure >30 cmH2O with symptoms. Neurological deterioration.',
                'prevention' => 'Annual fundoscopy for all residents. OCT monitoring for high-risk residents. Exercise protocols that minimize cephalad fluid shift. Early reporting of visual symptoms.',
                'is_emergency' => false,
                'search_keywords' => 'headache, vision, eyes, intracranial pressure, SANS, neuro, ICP',
            ],
        ];

        return array_merge($base, $this->generateVariants($base, 'neurological', 70));
    }

    private function psychologicalConditions(): array
    {
        $base = [
            [
                'name' => 'Earth Sickness',
                'lunar_variant_name' => 'Terra Siderans — Earth-sickness Syndrome',
                'icd10_code' => 'F43.20',
                'severity' => 'moderate',
                'description' => 'A complex psychological syndrome unique to lunar residents, characterized by profound longing for Earth, its ecosystems, and human social connections. Unlike homesickness, Earth-sickness involves the loss of an entire planet — its smells, weather, gravity, horizon, and natural light. The psychological weight of the small blue marble visible from lunar surface has been underestimated in early mission planning. Named "Terra Siderans" — the starved longing for Earth — in early lunar psychiatric literature, borrowing from the Heinlein-era concept of psychological cost of isolation.',
                'lunar_risk_factors' => 'Duration of lunar residence. Limited communication bandwidth with loved ones. Inability to return to Earth on personal decision. Habitat with no Earth view. Loss of connection to Earth\'s natural rhythms. Family events occurring without resident\'s presence.',
                'symptoms' => 'Persistent sadness focused on Earth and lost connections. Vivid dreams of Earth. Difficulty accepting lunar environment as "home." Emotional dysregulation around Earth communication times. Decreased engagement with habitat community.',
                'lunar_symptoms' => 'Distinctive from depression by its focus on a specific lost object (Earth) rather than generalized anhedonia. "Earth window" — lunar habitats with Earth-visible viewports report lower Earth-sickness rates. "Protocol 42 for Wellbeing" — the informal lunar mental health check-in system developed in the Artemis Quarter.',
                'diagnosis' => 'Structured interview using the Lunar Adaptation Assessment Scale. Distinguish from major depression, adjustment disorder. Timeline of symptoms relative to last Earth view, family communications.',
                'treatment' => 'Supportive psychotherapy, group therapy with fellow residents, scheduled high-quality Earth communications, VR Earth environments, meaningful lunar community engagement.',
                'treatment_lunar' => 'Priority communication scheduling (bandwidth guarantee from habitat admin). VR Earth environments (multiple biome simulations). Group "Earth story" sharing sessions. Meaningful role in habitat community. Psychological support group facilitation (peer counselors trained in lunar psychology). Grokking practice — deep acceptance mindfulness based on the Heinlein tradition, adapted as a moon-specific contemplative therapy. Antidepressants if criteria for major depression met.',
                'evacuation_criteria' => 'Active suicidal ideation with plan. Severe depression preventing function. Acute psychosis.',
                'prevention' => 'Pre-mission psychological screening and preparation. Earth-view habitat allocation. Communication priority scheduling. Strong lunar community culture. Rotating counselor availability.',
                'is_emergency' => false,
                'search_keywords' => 'homesickness, sad, Earth, longing, isolation, depression, psychological',
            ],
            [
                'name' => 'Isolation Syndrome',
                'lunar_variant_name' => 'Lunar Confinement Adaptation Disorder',
                'icd10_code' => 'F43.1',
                'severity' => 'moderate',
                'description' => 'Psychological deterioration from prolonged confinement in small spaces with a fixed group of people. Lunar habitats, regardless of construction, involve living within a bounded pressurized environment with the same individuals for months or years. The psychological toll of restricted personal space, limited privacy, and inescapable interpersonal dynamics is substantial.',
                'lunar_risk_factors' => 'Habitat size and design (smaller habitats, higher risk). Group composition (personality conflicts). Mission duration. Limited privacy spaces. Social role conflicts. Crew autonomy restrictions from Earth management.',
                'symptoms' => 'Irritability, interpersonal conflict, withdrawal, anxiety, sleep disruption, reduced performance, claustrophobic episodes.',
                'lunar_symptoms' => 'Classic presentation: the "third quarter phenomenon" — psychological nadir at about 3/4 through a mission. Interpersonal conflicts escalate predictably. Small personal territories become disproportionately important. "Smellblind" to habitat odors but hypersensitive to behavioral changes in crewmates.',
                'diagnosis' => 'Psychological Assessment Schedule for Isolated Teams (PASIT). Group dynamics assessment. Individual interviews. Performance metrics tracking.',
                'treatment' => 'Individual counseling, group mediation, privacy enhancement, structured recreation, short-term anxiolytics if indicated.',
                'treatment_lunar' => 'Enforce private space allocation. Schedule solo recreation time. Conflict mediation protocol (trained habitat commander role). Short-term benzodiazepines for acute anxiety (reserve, monitor for dependence). Tele-psychological support from Earth every 2 weeks. Group activities designed for small space. TANSTAAFL wellness board — reminder that psychological health is essential operational resource, not a luxury. "Don\'t Panic" protocol — simple, memorable distress signal protocol for any resident to request immediate private counseling.',
                'evacuation_criteria' => 'Violence risk to self or others. Severe psychiatric decompensation. Group dysfunction threatening mission safety.',
                'prevention' => 'Pre-mission team psychological assessment and team-building. Habitat design with private spaces. Communication protocols for interpersonal conflict. Pre-selected conflict resolution procedures.',
                'is_emergency' => false,
                'search_keywords' => 'isolation, confined, stress, claustrophobia, anxiety, small space, cabin fever',
            ],
            [
                'name' => 'Lunar Circadian Rhythm Disorder',
                'lunar_variant_name' => 'Lunar Day-Night Desynchronosis',
                'icd10_code' => 'G47.2',
                'severity' => 'moderate',
                'description' => 'Disruption of the human circadian rhythm by the lunar 29.5-day light cycle (14.7-day lunar day, 14.7-day lunar night). Human circadian rhythms entrain primarily to the 24-hour light-dark cycle of Earth. On the Moon, artificial lighting in habitats must substitute for natural light cues. Without careful light management, circadian desynchrony causes insomnia, performance degradation, metabolic dysfunction, and psychological symptoms.',
                'lunar_risk_factors' => 'Uncontrolled artificial lighting in habitat. Shift work patterns not matched to circadian protocol. Individual circadian rhythm variability. Stimulant use disrupting sleep. High stress workloads preventing adequate sleep.',
                'symptoms' => 'Insomnia, daytime sleepiness, cognitive performance degradation, mood disturbance, metabolic dysregulation.',
                'lunar_symptoms' => 'The full lunar day (29.5 Earth days) creates two high-risk periods: early residence when residents lose Earth light cues, and long-term residence when Earth-based internal clocks drift without external synchronization.',
                'diagnosis' => 'Actigraphy (wearable sleep tracking). Sleep logs. Salivary melatonin timing. Performance testing at standard times. Chronotype assessment.',
                'treatment' => 'Light therapy with appropriate spectrum and timing, melatonin supplementation, sleep schedule enforcement, stimulant reduction.',
                'treatment_lunar' => 'Circadian lighting protocol — habitat lighting programmed to simulate Earth\'s 24-hour light cycle regardless of lunar surface conditions. Blue-enriched white light in morning (10,000 lux equivalent). Dim warm light in evening. Melatonin 0.5-5 mg at consistent bedtime. Chronobiology consultation for persistent cases. Modafinil for essential shift work (avoid dependence). Prioritize 8-hour sleep protection in scheduling.',
                'evacuation_criteria' => 'Severe performance degradation creating safety risk. Psychiatric complications from chronic sleep deprivation.',
                'prevention' => 'Circadian lighting protocol from day 1. Consistent sleep schedules. Limited caffeine after early afternoon (habitat time). Chronotype-aware scheduling.',
                'is_emergency' => false,
                'search_keywords' => "can't sleep, insomnia, sleep disorder, circadian, light cycle, fatigue, shift work",
            ],
        ];

        return array_merge($base, $this->generateVariants($base, 'psychological', 80));
    }

    private function dermatologicalConditions(): array
    {
        $base = [
            [
                'name' => 'Radiation Dermatitis',
                'lunar_variant_name' => 'Lunar Surface Radiation Skin Syndrome',
                'icd10_code' => 'L58.0',
                'severity' => 'moderate',
                'description' => 'Skin damage from cosmic radiation and solar particle events. Lunar surface has no magnetosphere or significant atmosphere to attenuate radiation. EVA suits provide some protection but are not adequate for extended exposure during solar particle events. Skin is the most exposed organ to radiation during EVA.',
                'lunar_risk_factors' => 'Unshielded EVA during elevated radiation periods. Solar particle event exposure. Extended EVA cumulative exposure. Areas of suit with reduced shielding (joints). Individual radiation sensitivity differences.',
                'symptoms' => 'Erythema, dry desquamation, moist desquamation, skin breakdown, chronic fibrosis with repeated exposure.',
                'diagnosis' => 'Dosimetry records correlated with skin examination. Biopsy if unusual presentation.',
                'treatment' => 'Topical steroids for inflammation, barrier creams, wound care for desquamation.',
                'treatment_lunar' => 'Topical betamethasone for inflammatory phase. Barrier cream with high UV protection factor. Wound care if desquamation. Dosimetry review and EVA restriction. SPF monitoring during future EVA. Earth dermatology telemedicine for unusual presentations.',
                'evacuation_criteria' => 'Grade 4 radiation dermatitis (full thickness skin loss). Radiation-induced skin cancer (evacuation for definitive treatment).',
                'prevention' => 'Solar particle event monitoring and shelter protocol. EVA time limits based on cumulative dosimetry. Suit inspection for shielding defects.',
                'is_emergency' => false,
                'search_keywords' => 'skin, radiation, rash, sunburn, EVA, dermatitis, solar',
            ],
            [
                'name' => 'Pressure Suit Dermatitis',
                'lunar_variant_name' => 'EVA Suit Contact Dermatitis',
                'icd10_code' => 'L24.5',
                'severity' => 'minor',
                'description' => 'Contact dermatitis from prolonged pressure suit wear. The materials, pressure, heat, and sweat within EVA suits create ideal conditions for skin breakdown. Extended EVA operations are particularly problematic.',
                'lunar_risk_factors' => 'Duration of EVA. Individual skin sensitivity. Pre-existing skin conditions. Suit fit issues. Inadequate suit hygiene between EVA operations. High-sweat activities in suit.',
                'symptoms' => 'Erythema, itching, vesicle formation, skin breakdown at pressure points. Fungal superinfection in moist areas.',
                'diagnosis' => 'Clinical examination of pressure points after EVA. Culture if secondary infection suspected.',
                'treatment' => 'Barrier creams, topical steroids, EVA reduction, antifungals if secondary infection.',
                'treatment_lunar' => 'Apply barrier cream (zinc oxide or petroleum jelly) to pressure points before EVA. Hydrocortisone 1% for inflammation. Clotrimazole cream for fungal superinfection. Review suit fit. EVA rest period if severe. Document and report to suit maintenance team.',
                'evacuation_criteria' => 'Rarely requires evacuation. Secondary infection causing systemic sepsis.',
                'prevention' => 'Pre-EVA barrier cream application. Suit fit optimization. Regular suit hygiene. Post-EVA skin inspection protocol.',
                'is_emergency' => false,
                'search_keywords' => 'suit rash, skin irritation, EVA dermatitis, pressure sores',
            ],
        ];

        return array_merge($base, $this->generateVariants($base, 'dermatological', 60));
    }

    private function ophthalmologicalConditions(): array
    {
        $base = [
            [
                'name' => 'Choroidal Folds',
                'lunar_variant_name' => 'SANS-related Choroidal Folds',
                'icd10_code' => 'H31.8',
                'severity' => 'moderate',
                'description' => 'Folds in the choroidal layer of the retina, associated with intracranial hypertension and cephalad fluid shift in low gravity. Part of the Spaceflight-Associated Neuro-Ocular Syndrome (SANS), these changes indicate elevated intraocular or intracranial pressure and can impair visual acuity.',
                'lunar_risk_factors' => 'Extended lunar residence. Cephalad fluid shift. Elevated intracranial pressure. Individual anatomical susceptibility.',
                'symptoms' => 'Blurred vision, metamorphopsia (distorted vision), reduced visual acuity.',
                'diagnosis' => 'Fundoscopy, optical coherence tomography (OCT), B-scan ultrasonography.',
                'treatment' => 'Treat underlying ICP elevation, positional therapy, close monitoring.',
                'treatment_lunar' => 'Acetazolamide for ICP reduction. Head elevation during sleep. Monthly OCT monitoring. Earth ophthalmology telemedicine if progressive. Vision screening at every medical review.',
                'evacuation_criteria' => 'Progressive vision loss. Significant visual acuity decline. Macular involvement.',
                'prevention' => 'Annual OCT screening, regular ICP monitoring, early treatment of SANS.',
                'is_emergency' => false,
                'search_keywords' => 'vision, eyes, blurry, retina, SANS, intracranial pressure',
            ],
            [
                'name' => 'Optic Disc Edema',
                'lunar_variant_name' => 'Lunar Papilledema',
                'icd10_code' => 'H47.1',
                'severity' => 'severe',
                'description' => 'Swelling of the optic disc caused by elevated intracranial pressure. A finding of significant concern in lunar residents, indicating that ICP has risen sufficiently to affect the optic nerve. If not treated, can progress to permanent vision loss.',
                'lunar_risk_factors' => 'Extended lunar residence, cephalad fluid shift, high-intensity exercise.',
                'symptoms' => 'Transient visual obscurations, progressive visual field loss, visual acuity decline.',
                'diagnosis' => 'Fundoscopy showing disc swelling (papilledema grading 1-5), OCT showing RNFL thickening.',
                'treatment' => 'ICP reduction (acetazolamide, position), close monitoring, possible CSF diversion on Earth.',
                'treatment_lunar' => 'Acetazolamide 500mg BID. Head elevation. Restrict vigorous exercise. Monthly OCT follow-up. Earth neurology/ophthalmology telemedicine urgently. Evacuation planning if grade 3+ or progressive.',
                'evacuation_criteria' => 'Grade 4-5 papilledema. Progressive visual field loss. Visual acuity decline >1 Snellen line.',
                'prevention' => 'Regular fundoscopy screening, ICP monitoring, early treatment of intracranial hypertension.',
                'is_emergency' => true,
                'search_keywords' => 'vision loss, papilledema, optic disc, eye, intracranial pressure',
            ],
        ];

        return array_merge($base, $this->generateVariants($base, 'ophthalmological', 50));
    }

    private function oncologicalConditions(): array
    {
        $base = [
            [
                'name' => 'Radiation-Induced Malignancy',
                'lunar_variant_name' => 'Cosmic Ray Carcinogenesis Syndrome',
                'icd10_code' => 'C80.1',
                'severity' => 'critical',
                'description' => 'Cancer resulting from accumulated cosmic radiation exposure. Without Earth\'s magnetic field and atmospheric shielding, lunar residents receive approximately 200-400 mSv/year — compared to about 3 mSv/year on Earth\'s surface. Heavy ions from galactic cosmic rays are particularly carcinogenic, causing complex double-strand DNA breaks that are difficult for human repair mechanisms to address.',
                'lunar_risk_factors' => 'Duration of lunar residence. Solar particle events (acute high-dose exposure). Individual radiosensitivity. Inadequate habitat shielding. Accumulated lifetime radiation dose. Smoking or other carcinogen exposure.',
                'symptoms' => 'Highly variable by tumor type and location. Fatigue, weight loss, and pain are common to many malignancies.',
                'lunar_symptoms' => 'Radiation-induced cancers in lunar residents show higher rates of leukemia and lung cancer than Earth populations at matched ages. Solid tumors may develop 10-30 years after high radiation exposure.',
                'diagnosis' => 'Cancer screening protocols adapted for lunar population. Blood counts for leukemia surveillance. CT scan capability limited — portable ultrasound for accessible tumors. Biopsy for tissue confirmation.',
                'treatment' => 'Cancer treatment requires evacuation to Earth or a fully-equipped orbital hospital. Lunar medical bay can provide symptom management only.',
                'treatment_lunar' => 'Symptom management: pain control, anti-emetics, appetite support. Immediate evacuation planning for confirmed malignancy. Earth oncology telemedicine for staging and treatment planning during evacuation process. Maintain dignity and information sharing with patient. Psychological support — the weight of a cancer diagnosis in lunar context is profound.',
                'evacuation_criteria' => 'All confirmed malignancies requiring treatment. Suspected hematological malignancy. Suspected malignancy with urgent surgical or oncological intervention needed.',
                'prevention' => 'Annual blood count monitoring. Comprehensive radiation dosimetry tracking. Lifetime dose limits (career limit: 350 mSv/year averaged). Solar particle event shelter protocol. Habitat shielding optimization.',
                'is_emergency' => false,
                'search_keywords' => 'cancer, radiation, tumor, malignancy, cosmic rays, carcinoma, leukemia',
            ],
        ];

        return array_merge($base, $this->generateVariants($base, 'oncological', 40));
    }

    private function infectiousConditions(): array
    {
        $base = [
            [
                'name' => 'Closed-Habitat Respiratory Illness',
                'lunar_variant_name' => 'Lunar Habitat Respiratory Outbreak',
                'icd10_code' => 'J06.9',
                'severity' => 'moderate',
                'description' => 'Respiratory infections transmitted in the closed habitat environment. All residents share recirculated air; a respiratory pathogen introduced by a new arrival or reactivated from a carrier can rapidly infect an entire colony. The mathematical models for epidemic spread in closed habitats (inspired by predictive epidemiology principles) show frightening propagation rates.',
                'lunar_risk_factors' => 'New arrivals introducing fresh pathogens. Reactivation of latent viruses under stress and radiation. Shared air circulation without adequate HEPA filtration. High physical proximity of residents. Immunosuppression from chronic stress and radiation.',
                'symptoms' => 'Fever, cough, rhinorrhea, sore throat, malaise. Severity varies by pathogen.',
                'lunar_symptoms' => 'Colony-wide impact when respiratory illness spreads. Simultaneous illness in multiple essential personnel creates operational safety risks. Standard public health measures are difficult in small habitats — everyone uses the same air.',
                'diagnosis' => 'Clinical assessment, rapid antigen testing (flu, COVID-19 variants), sputum culture if available.',
                'treatment' => 'Supportive care, antivirals if indicated (oseltamivir for influenza), antibiotics if secondary bacterial infection.',
                'treatment_lunar' => 'Cohort isolation protocol (all symptomatic residents). N95 masking required in common areas during outbreak. Oseltamivir prophylaxis for high-risk contacts. Increase HEPA filtration cycling. Enhanced hand hygiene. Telemedicine infection control consultation. Outbreak reporting to Earth. Conserve critical personnel by prophylactic oseltamivir for operators with essential duties.',
                'evacuation_criteria' => 'Respiratory failure requiring ventilatory support. Outbreak overwhelming medical bay capacity. Novel pathogen with unknown transmission or severity profile.',
                'prevention' => 'Pre-departure vaccination protocol. Quarantine period for new arrivals. Air quality monitoring. Respiratory hygiene training. Outbreak response team designation.',
                'is_emergency' => false,
                'search_keywords' => 'cold, flu, respiratory, infection, outbreak, colony, virus, cough',
            ],
        ];

        return array_merge($base, $this->generateVariants($base, 'infectious', 60));
    }

    private function traumaConditions(): array
    {
        $base = [
            [
                'name' => 'EVA Suit Breach Injury',
                'lunar_variant_name' => 'Explosive Decompression Event',
                'icd10_code' => 'T70.3',
                'severity' => 'critical',
                'description' => 'Life-threatening injury from breach of EVA suit integrity, exposing resident to near-vacuum of lunar surface. The Moon has essentially no atmosphere (surface pressure <10^-12 Pa). Even a small breach causes explosive decompression of the suit interior. Survival window measured in seconds to minutes depending on breach size.',
                'lunar_risk_factors' => 'Regolith sharp edge penetration of suit. Equipment collision during mining operations. Micrometeorite impact. Suit degradation from UV and radiation. Human error in suit donning.',
                'symptoms' => 'Immediate: burning pain at breach site, rapid depressurization sensation, then loss of consciousness as cerebral hypoxia occurs within 10-15 seconds of breath-hold failure.',
                'lunar_symptoms' => 'The injured EVA partner usually cannot self-rescue. Companion EVA is mandatory for this reason. Survival depends on immediate companion action within seconds.',
                'diagnosis' => 'Clinical and radiological evaluation post-rescue. Decompression sickness, barotrauma, hypothermia, radiation exposure assessment.',
                'treatment' => 'Immediate suit repressurization or buddy rescue to airlock. Hyperbaric oxygen treatment if available. Decompression sickness management.',
                'treatment_lunar' => 'IMMEDIATE RESCUE PROTOCOL: Companion applies emergency suit patch (all EVA suits carry). Buddy breathing through emergency O2 connection. Rapid airlock return. Medical bay: oxygen 100%, decompression sickness protocol, hypothermia treatment if needed, trauma assessment. Earth telemedicine immediately — all EVA breach events reported to mission control. Hyperbaric chamber if available at habitat.',
                'evacuation_criteria' => 'All EVA breach events warrant evacuation consideration. Severe decompression injury. Trauma requiring surgical intervention.',
                'prevention' => 'Two-person EVA minimum. Daily suit integrity inspection. Sharp hazard mapping in work areas. Emergency patch training and drills. Never solo EVA.',
                'is_emergency' => true,
                'search_keywords' => 'suit breach, vacuum, EVA emergency, decompression, explosive, airlock',
            ],
            [
                'name' => 'Mining Crush Injury',
                'lunar_variant_name' => 'Regolith Excavation Trauma',
                'icd10_code' => 'S31.0',
                'severity' => 'severe',
                'description' => 'Crush injuries from mining equipment, regolith collapse, or heavy equipment accidents. Mining is the highest-risk occupation in lunar habitation, with unique trauma patterns from the combination of heavy equipment operating in 1/6 gravity (equipment is lighter but inertia is unchanged) and the brittleness of vacuum-deposited regolith which can collapse unexpectedly.',
                'lunar_risk_factors' => 'Powered excavation equipment. Regolith tunnel collapse. Equipment rollovers (lighter = more unstable in low g). Falls from heights (falls are slower but impacts are still dangerous due to suit mass). Remote operation without immediate assistance.',
                'symptoms' => 'Crush syndrome: tissue destruction, hyperkalemia, myoglobinuria, acute kidney injury. Traumatic injuries variable.',
                'lunar_symptoms' => 'Crush syndrome physiology unchanged by lunar gravity but treatment options dramatically limited. Compartment syndrome must be recognized early — fasciotomy may be required in the field.',
                'diagnosis' => 'Clinical assessment, CPK, electrolytes, urinalysis for myoglobin. X-rays for fractures. FAST exam (bedside ultrasound) for internal hemorrhage.',
                'treatment' => 'Fluid resuscitation, monitoring, treating metabolic complications, surgical debridement.',
                'treatment_lunar' => 'IV access and aggressive fluid resuscitation (NS initially, then LR). Target urine output 1-2 mL/kg/hr with alkalinization. Monitor potassium — treat hyperkalemia aggressively (calcium gluconate, insulin/glucose, sodium bicarbonate). If compartment syndrome: urgent fasciotomy (trained surgeon only). Blood transfusion from emergency supply. Telemedicine trauma surgery consultation. Prepare evacuation if renal failure developing.',
                'evacuation_criteria' => 'Internal hemorrhage requiring surgery. Acute renal failure. Multi-organ failure. Major fractures requiring orthopedic repair.',
                'prevention' => 'Equipment safety protocols. Regolith stability assessment before excavation. Two-person work teams. Emergency beacon on all mining equipment. Monthly safety drills.',
                'is_emergency' => true,
                'search_keywords' => 'mining injury, crush, trauma, excavation, emergency, accident',
            ],
        ];

        return array_merge($base, $this->generateVariants($base, 'trauma', 70));
    }

    private function toxicologicalConditions(): array
    {
        $base = [
            [
                'name' => 'Regolith Toxicity',
                'lunar_variant_name' => 'Lunar Dust Chemical Toxicity',
                'icd10_code' => 'T65.891',
                'severity' => 'moderate',
                'description' => 'Chemical toxicity from exposure to lunar regolith components beyond physical particle inhalation. Lunar regolith contains reactive oxygen species, heavy metals, and glass shards. It also contains traces of volatiles deposited by solar wind, including helium-3, hydrogen, and other elements. The chemical toxicity profile is distinct from the mechanical pneumoconiosis risk.',
                'lunar_risk_factors' => 'Direct skin or mucous membrane contact with regolith. Ingestion (hand-to-mouth during EVA food/water intake). Inadequate decontamination after EVA. Suit contamination of habitat air.',
                'symptoms' => 'Skin and eye irritation on contact. Nausea if ingested. Toxic alveolitis if inhaled in large quantities.',
                'diagnosis' => 'Clinical history of regolith exposure. Bronchoalveolar lavage particle analysis. Heavy metal blood levels for chronic exposure.',
                'treatment' => 'Decontamination, supportive care, specific antidotes if indicated.',
                'treatment_lunar' => 'Skin: copious water irrigation. Eye exposure: eye wash station irrigation 15 min minimum. Ingestion: activated charcoal if large amount, supportive care. Inhalation: remove from exposure, bronchodilators, corticosteroids. Baseline heavy metal levels and 3-month follow-up.',
                'evacuation_criteria' => 'Severe toxic alveolitis. Heavy metal poisoning with organ involvement.',
                'prevention' => 'Rigorous decontamination protocol. No eating/drinking without removing outer suit gloves. Habitat air quality monitoring.',
                'is_emergency' => false,
                'search_keywords' => 'dust, regolith, chemical, toxic, skin, inhalation, contamination',
            ],
        ];

        return array_merge($base, $this->generateVariants($base, 'toxicological', 40));
    }

    private function nutritionalConditions(): array
    {
        $base = [
            [
                'name' => 'Vitamin D Deficiency',
                'lunar_variant_name' => 'Lunar Photosynthesis Deficit',
                'icd10_code' => 'E55.9',
                'severity' => 'moderate',
                'description' => 'Deficiency of vitamin D from absence of UV light exposure in lunar habitats. Humans synthesize most of their vitamin D through UVB irradiation of skin; lunar habitats do not provide this, and without supplementation, deficiency is virtually universal in lunar residents. Vitamin D is critical for bone health (compounding osteoporosis risk), immune function, and psychological wellbeing.',
                'lunar_risk_factors' => 'Universal in unsupplemented residents. Habitats with no UV-transparent windows. Darker skin (higher melanin, lower UVB efficiency). Higher latitude habitats with less reflected sunlight.',
                'symptoms' => 'Bone pain, fatigue, muscle weakness, depression, frequent infections, deficiency-exacerbated osteoporosis.',
                'diagnosis' => 'Serum 25-hydroxyvitamin D. Target: >75 nmol/L. Quarterly monitoring.',
                'treatment' => 'Vitamin D3 supplementation 2000-4000 IU daily. Higher doses for severe deficiency.',
                'treatment_lunar' => 'Universal vitamin D3 supplementation: 2000-4000 IU daily for maintenance. Severe deficiency (<25 nmol/L): loading dose protocol (50,000 IU weekly for 6 weeks, then maintenance). Calcium supplementation concurrent. Monitor magnesium (required for vitamin D activation). UV light therapy lamps in habitat common areas (safe spectrum, scheduled exposure).',
                'evacuation_criteria' => 'Rarely requires evacuation. Vitamin D-related hypercalcemia in excess supplementation.',
                'prevention' => 'Universal supplementation protocol from day 1. Quarterly monitoring. UV lamp access in all habitats.',
                'is_emergency' => false,
                'search_keywords' => 'vitamin D, bone, fatigue, sunlight, deficiency, supplement, moon',
            ],
        ];

        return array_merge($base, $this->generateVariants($base, 'nutritional', 50));
    }

    private function endocrineConditions(): array
    {
        $base = [
            [
                'name' => 'Stress-Induced Hypercortisolism',
                'lunar_variant_name' => 'Chronic Lunar Stress Response',
                'icd10_code' => 'E24.9',
                'severity' => 'moderate',
                'description' => 'Prolonged elevation of cortisol from chronic psychosocial stress and physical demands of lunar residence. Chronically elevated cortisol contributes to immune suppression, bone loss, muscle wasting, metabolic syndrome, and cardiovascular disease — all of which have unique lunar interactions.',
                'lunar_risk_factors' => 'Occupational stress (high-consequence tasks). Confinement stress. Sleep disruption. Physical exercise demands. Uncertainty about Earth return. Radiation anxiety.',
                'symptoms' => 'Weight gain, fatigue, mood disturbance, recurrent infections, slow healing, hypertension.',
                'diagnosis' => 'Morning cortisol, 24-hour urinary cortisol, DHEA-S level, diurnal cortisol pattern.',
                'treatment' => 'Stress reduction, sleep optimization, psychological support, adaptogens.',
                'treatment_lunar' => 'Stress management program. Mindfulness-based therapy (grokking practice — deep acceptance based on Heinlein-inspired therapy adapted for lunar residents). Sleep optimization. Ashwagandha supplementation (adaptogen with evidence base). Earth telemedicine endocrinology if cortisol persistently elevated. Rule out Cushing\'s syndrome if severe.',
                'evacuation_criteria' => 'Cushing\'s syndrome with complications. Adrenal crisis.',
                'prevention' => 'Pre-mission stress management training. Habitat design with adequate rest areas. Workload management. Psychological support infrastructure.',
                'is_emergency' => false,
                'search_keywords' => 'stress, cortisol, hormones, fatigue, adrenal, weight gain',
            ],
        ];

        return array_merge($base, $this->generateVariants($base, 'endocrine', 50));
    }

    private function gastrointestinalConditions(): array
    {
        $base = [
            [
                'name' => 'Functional Dyspepsia',
                'lunar_variant_name' => 'Lunar Diet Adaptation Syndrome',
                'icd10_code' => 'K30',
                'severity' => 'minor',
                'description' => 'Upper gastrointestinal discomfort in lunar residents adapting to the hydroponic-based lunar diet. The change in microbiome, food textures, flavor profiles, and caloric composition can cause significant gastrointestinal adjustment symptoms in new residents.',
                'lunar_risk_factors' => 'New lunar arrival adapting to hydroponic diet. Limited food variety. High stress levels affecting gut-brain axis. Antibiotic use disrupting microbiome. Water quality variations in habitat.',
                'symptoms' => 'Bloating, early satiety, nausea, epigastric discomfort, altered bowel habits.',
                'diagnosis' => 'Clinical assessment, dietary history, H. pylori testing, dietary diary.',
                'treatment' => 'Dietary modification, acid suppression, prokinetics, probiotics.',
                'treatment_lunar' => 'Dietary diversity maximization within lunar options. Proton pump inhibitor (omeprazole) for acid symptoms. Metoclopramide for nausea. Probiotics from habitat food science team. Gradually introduce new food items. Psychological support (food is central to psychological wellbeing in confined environments). TANSTAAFL Nutrition Advisory — habitat food guide adapted for lunar conditions.',
                'evacuation_criteria' => 'Upper GI hemorrhage. Perforation. Obstruction. Weight loss >10% from baseline.',
                'prevention' => 'Dietary acclimatization protocol for new arrivals. Food variety in meal planning. Microbiome monitoring.',
                'is_emergency' => false,
                'search_keywords' => 'stomach, nausea, digestion, diet, bloating, lunar food, gastrointestinal',
            ],
        ];

        return array_merge($base, $this->generateVariants($base, 'gastrointestinal', 60));
    }

    private function renalConditions(): array
    {
        $base = [
            [
                'name' => 'Nephrolithiasis',
                'lunar_variant_name' => 'Lunar Kidney Stone Disease',
                'icd10_code' => 'N20.0',
                'severity' => 'severe',
                'description' => 'Kidney stone formation, significantly elevated in lunar residents from dehydration, altered calcium metabolism in low gravity, vitamin D supplementation effects, and immobility during EVA preparation. Kidney stones in a lunar medical bay without urological surgical capacity present a management challenge.',
                'lunar_risk_factors' => 'Chronic dehydration from EVA and habitat conditions. Hypercalciuria from bone resorption in low gravity. Vitamin D supplementation increasing calcium absorption. Reduced physical activity. Acidic urine from high-protein diet.',
                'symptoms' => 'Severe colicky flank pain, hematuria, nausea, vomiting, fever if infected.',
                'diagnosis' => 'Clinical (classic presentation), urinalysis (hematuria, crystals), portable ultrasound (hydronephrosis), X-ray for calcifications.',
                'treatment' => 'Analgesics, hydration, alpha-blockers for passage, lithotripsy or ureteroscopy for stones not passing.',
                'treatment_lunar' => 'Pain management: ketorolac IM and morphine. IV hydration (1L NS over 1 hour). Tamsulosin 0.4mg daily (alpha-blocker for stone passage). Strain urine for stone analysis. If fever: urgent antibiotics (ciprofloxacin) and telemedicine consultation — infected stone is urological emergency. Without lithotripsy: conservative management with mandatory evacuation planning for >10mm stone. Earth urology telemedicine for all cases.',
                'evacuation_criteria' => 'Infected obstructing stone (urological emergency). Stone >10mm unlikely to pass. Uncontrolled pain despite maximum analgesia. Solitary kidney obstruction.',
                'prevention' => 'Hydration protocol enforced (minimum 2.5L/day). Dietary calcium maintained at 800-1200mg (not zero — paradoxically prevents stones). Reduce dietary oxalate. Potassium citrate supplementation for high-risk residents.',
                'is_emergency' => true,
                'search_keywords' => 'kidney stone, flank pain, urinary, renal colic, blood in urine, kidney',
            ],
        ];

        return array_merge($base, $this->generateVariants($base, 'renal', 40));
    }

    private function hematologicalConditions(): array
    {
        $base = [
            [
                'name' => 'Anemia',
                'lunar_variant_name' => 'Lunar Space Anemia',
                'icd10_code' => 'D64.9',
                'severity' => 'moderate',
                'description' => 'Reduced red blood cell mass observed in lunar residents, thought to result from initial plasma volume reduction causing hemoconcentration followed by destruction of excess red blood cells. Also called "space anemia," this is distinct from Earth anemias and appears to persist for at least a year of lunar residence.',
                'lunar_risk_factors' => 'Duration of lunar residence. Oxidative stress from radiation. Iron metabolism changes. Dietary iron adequacy in hydroponic diet. Chronic inflammatory state from stress.',
                'symptoms' => 'Fatigue, dyspnea on exertion, pallor, tachycardia, cognitive difficulty.',
                'diagnosis' => 'CBC with differential, iron studies, vitamin B12, folate, reticulocyte count, peripheral smear.',
                'treatment' => 'Correct underlying cause. Iron supplementation, B12, folate if deficient. Erythropoietin in severe cases.',
                'treatment_lunar' => 'Comprehensive anemia workup. Iron supplementation if iron-deficient. Vitamin B12 injection if deficient (absorption may be impaired in lunar diet). Dietary iron optimization from hydroponic legumes. Monitor hemoglobin quarterly. Reserve erythropoietin for severe cases with symptom impact. Telemedicine hematology if hemolytic anemia suspected.',
                'evacuation_criteria' => 'Hemoglobin <7 g/dL symptomatic. Hemolytic crisis. Aplastic anemia.',
                'prevention' => 'Annual CBC. Dietary iron monitoring. B12 supplementation for residents on fully plant-based lunar diet.',
                'is_emergency' => false,
                'search_keywords' => 'anemia, fatigue, blood, hemoglobin, iron, red blood cells, dizzy',
            ],
        ];

        return array_merge($base, $this->generateVariants($base, 'hematological', 40));
    }

    private function immunologicalConditions(): array
    {
        $base = [
            [
                'name' => 'Immunosuppression',
                'lunar_variant_name' => 'Lunar Immune Dysregulation Syndrome',
                'icd10_code' => 'D84.9',
                'severity' => 'moderate',
                'description' => 'Impaired immune function from radiation exposure, chronic stress, sleep disruption, and microbial environment changes in lunar habitats. Radiation suppresses bone marrow stem cells. Chronic stress elevates cortisol, which is immunosuppressive. The highly controlled habitat microbial environment may also reduce immune stimulation.',
                'lunar_risk_factors' => 'Cumulative radiation exposure. Chronic psychosocial stress. Sleep disruption. Microbiome changes from confined habitat. Nutritional deficiencies (vitamin D, zinc, etc.).',
                'symptoms' => 'Frequent infections, prolonged illness, reactivation of latent viruses (EBV, VZV, CMV), slow wound healing.',
                'diagnosis' => 'Lymphocyte count and subsets, immunoglobulin levels, NK cell function, CBC with differential.',
                'treatment' => 'Address underlying causes, prophylactic antibiotics for severe cases, immunoglobulin if deficient.',
                'treatment_lunar' => 'Optimize nutrition (zinc, vitamin D, vitamin C). Sleep protocol enforcement. Stress management. Vaccination boosters (annual influenza, VZV booster). Monitor for viral reactivation. Prophylactic acyclovir for VZV reactivation risk. Earth immunology telemedicine for significant immunodeficiency. No immunosuppressive medications unless absolutely necessary.',
                'evacuation_criteria' => 'Severe combined immunodeficiency manifestation. Overwhelming infection in immunocompromised host. Bone marrow failure.',
                'prevention' => 'Radiation dose monitoring and limits. Nutrition protocol. Sleep protection. Psychological support. Pre-mission vaccination completion.',
                'is_emergency' => false,
                'search_keywords' => 'immune, infection, resistance, radiation, fatigue, repeated illness',
            ],
        ];

        return array_merge($base, $this->generateVariants($base, 'immunological', 40));
    }

    private function reproductiveConditions(): array
    {
        $base = [
            [
                'name' => 'Radiation Effects on Fertility',
                'lunar_variant_name' => 'Lunar Gonadal Radiation Syndrome',
                'icd10_code' => 'N46',
                'severity' => 'severe',
                'description' => 'Impairment of fertility from gonadal radiation exposure. The gonads are among the most radiosensitive tissues in the body. Lunar residents accumulate radiation doses that, over time, may impact fertility and increase risk of gonadal malignancy.',
                'lunar_risk_factors' => 'Cumulative radiation dose. Solar particle events without adequate shielding. Extended mission duration. Individual radiosensitivity.',
                'symptoms' => 'May be asymptomatic until fertility tested. Menstrual irregularities in females. Abnormal semen analysis in males.',
                'diagnosis' => 'Hormonal assessment (FSH, LH, estradiol/testosterone). Semen analysis. Ovarian reserve assessment.',
                'treatment' => 'Fertility preservation before lunar deployment, hormone supplementation, assisted reproduction on return.',
                'treatment_lunar' => 'Pre-mission sperm/oocyte cryopreservation highly recommended. Gonadal shielding during solar particle events. Quarterly hormone monitoring. Earth reproductive endocrinology telemedicine. Temporary hormone replacement if premature ovarian insufficiency. Psychological support — infertility concerns in confined habitat are psychologically complex.',
                'evacuation_criteria' => 'Gonadal malignancy. Premature ovarian failure requiring specialized management.',
                'prevention' => 'Mandatory pre-mission fertility preservation counseling. Radiation dose limits. Gonadal shielding protocols.',
                'is_emergency' => false,
                'search_keywords' => 'fertility, radiation, reproductive, gonads, hormones, menstrual',
            ],
        ];

        return array_merge($base, $this->generateVariants($base, 'reproductive', 30));
    }

    private function generateVariants(array $baseConditions, string $system, int $targetCount): array
    {
        $variants = [];
        $generated = 0;
        $baseCount = count($baseConditions);

        $modifiers = [
            'acute' => 'Acute', 'chronic' => 'Chronic', 'secondary' => 'Secondary',
            'post_eva' => 'Post-EVA', 'radiation' => 'Radiation-Associated',
            'exercise' => 'Exercise-Induced', 'dietary' => 'Diet-Related',
            'occupational' => 'Occupational', 'pediatric' => 'Pediatric Lunar',
            'elderly' => 'Long-Term Resident', 'mild' => 'Subclinical',
        ];

        $severities = ['minor', 'minor', 'moderate', 'moderate', 'moderate', 'severe', 'severe', 'critical'];

        while ($generated < $targetCount) {
            $base = $baseConditions[$generated % $baseCount];
            $modKey = array_keys($modifiers)[$generated % count($modifiers)];
            $modLabel = $modifiers[$modKey];

            $variantName = "{$modLabel} " . ($base['lunar_variant_name'] ?? $base['name']);
            $severity = $severities[$generated % count($severities)];

            $variants[] = [
                'name' => "{$modLabel} " . $base['name'],
                'lunar_variant_name' => $variantName,
                'icd10_code' => $base['icd10_code'] ?? null,
                'severity' => $severity,
                'description' => "{$modLabel} presentation of {$base['name']} in lunar residents. " . Str::limit($base['description'], 300),
                'lunar_risk_factors' => $base['lunar_risk_factors'] ?? "Lunar environmental factors including reduced gravity, radiation, and isolation may contribute to this presentation.",
                'symptoms' => $base['symptoms'],
                'lunar_symptoms' => $base['lunar_symptoms'] ?? null,
                'diagnosis' => $base['diagnosis'],
                'treatment' => $base['treatment'],
                'treatment_lunar' => $base['treatment_lunar'] ?? null,
                'evacuation_criteria' => $base['evacuation_criteria'] ?? null,
                'prevention' => $base['prevention'] ?? null,
                'is_emergency' => $severity === 'critical' && ($base['is_emergency'] ?? false),
                'search_keywords' => ($base['search_keywords'] ?? '') . ", {$system}, {$modKey}",
            ];
            $generated++;
        }

        return $variants;
    }
}
