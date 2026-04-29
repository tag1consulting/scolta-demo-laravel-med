<?php

namespace App\Console\Commands;

use App\Models\Medication;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class LunarSeedMedications extends Command
{
    protected $signature = 'lunar:seed-medications {--truncate : Truncate existing data first}';
    protected $description = 'Seed ~3,000 medications adapted for the lunar pharmacy';

    public function handle(): int
    {
        if ($this->option('truncate')) {
            Medication::truncate();
            $this->info('Existing medications truncated.');
        }

        $this->info('Seeding lunar pharmacy medications...');

        $medications = $this->getMedications();
        $bar = $this->output->createProgressBar(count($medications));
        $bar->start();

        $total = 0;
        foreach ($medications as $data) {
            $slug = Str::slug($data['generic_name']);
            $counter = 1;
            $baseSlug = $slug;
            while (Medication::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $counter++;
            }

            Medication::create([
                'generic_name'       => $data['generic_name'],
                'slug'               => $slug,
                'brand_names'        => $data['brand_names'] ?? null,
                'drug_class'         => $data['drug_class'],
                'mechanism'          => $data['mechanism'],
                'indications'        => $data['indications'],
                'dosing_standard'    => $data['dosing_standard'],
                'dosing_lunar'       => $data['dosing_lunar'] ?? null,
                'storage_standard'   => $data['storage_standard'] ?? null,
                'storage_lunar'      => $data['storage_lunar'] ?? null,
                'supply_chain_notes' => $data['supply_chain_notes'] ?? null,
                'interactions'       => $data['interactions'] ?? null,
                'contraindications'  => $data['contraindications'] ?? null,
                'side_effects'       => $data['side_effects'] ?? null,
                'alternatives'       => $data['alternatives'] ?? null,
                'who_essential'      => $data['who_essential'] ?? false,
                'lunar_critical'     => $data['lunar_critical'] ?? false,
                'search_keywords'    => $data['search_keywords'] ?? null,
            ]);
            $total++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("\nSeeded {$total} medications.");
        return self::SUCCESS;
    }

    private function getMedications(): array
    {
        $base = $this->getBaseMedications();
        return array_merge($base, $this->generateVariants($base));
    }

    private function getBaseMedications(): array
    {
        return [
            // Analgesics & Pain Management
            [
                'generic_name'       => 'Acetaminophen (Paracetamol)',
                'brand_names'        => 'Tylenol, Panadol',
                'drug_class'         => 'Analgesic / Antipyretic',
                'mechanism'          => 'Inhibits prostaglandin synthesis in the CNS; reduces fever via hypothalamic heat-regulating center.',
                'indications'        => 'Mild to moderate pain, fever. First-line analgesic for most lunar residents due to favorable safety profile and lack of platelet effects.',
                'dosing_standard'    => '325-1000 mg every 4-6 hours. Maximum 4g/day.',
                'dosing_lunar'       => 'Standard dosing applies. In lunar residents, hepatic function may be mildly altered by radiation — monitor liver enzymes quarterly in regular users. Maximum 3g/day recommended for long-term lunar residents. Bioavailability appears unchanged in 1/6g environment.',
                'storage_standard'   => 'Room temperature, avoid moisture.',
                'storage_lunar'      => 'Store at 15-25°C. Protect from radiation exposure in shielded storage. Stock minimum 365-day supply for habitat population. Tablet form preferred over liquid (better radiation and temperature stability). Rotation protocol: FIFO with 18-month expiry check.',
                'supply_chain_notes' => 'WHO Essential Medicine. Resupply priority: HIGH. Generic form acceptable. Can be synthesized via multi-step chemical route if supply interrupted (requires pharmaceutical capability). Stability marginally reduced by radiation — use shielded storage.',
                'interactions'       => 'Warfarin (increases INR at high doses). Alcohol (increases hepatotoxicity risk). Rifampicin (reduces efficacy).',
                'contraindications'  => 'Severe hepatic impairment. Known hypersensitivity.',
                'side_effects'       => 'Hepatotoxicity in overdose. Otherwise excellent tolerability.',
                'alternatives'       => 'NSAIDs (if no contraindication). Tramadol for moderate pain not responding to acetaminophen alone.',
                'who_essential'      => true,
                'lunar_critical'     => true,
                'search_keywords'    => 'pain, fever, analgesic, Tylenol, paracetamol, headache',
            ],
            [
                'generic_name'       => 'Ibuprofen',
                'brand_names'        => 'Advil, Motrin, Nurofen',
                'drug_class'         => 'NSAID (Non-Steroidal Anti-Inflammatory Drug)',
                'mechanism'          => 'Non-selective COX-1/COX-2 inhibitor. Reduces prostaglandin synthesis, decreasing inflammation, pain, and fever.',
                'indications'        => 'Pain, inflammation, fever, dysmenorrhea, arthritis. Particularly useful for musculoskeletal injuries common in lunar workers. Anti-inflammatory effect valuable for EVA-related strain and low-g adaptation pains.',
                'dosing_standard'    => '200-800 mg every 6-8 hours. Maximum 3200 mg/day.',
                'dosing_lunar'       => 'Standard dosing applies. Monitor blood pressure (NSAIDs can elevate BP — concern in lunar hypertensive population). Limit use in residents with high radiation exposure (increased GI bleeding risk from radiation-induced mucosal damage). Prefer acetaminophen in residents on anticoagulants.',
                'storage_standard'   => 'Room temperature, protect from moisture.',
                'storage_lunar'      => 'Store at 15-25°C. Radiation shielded storage. Tablet form preferred. 24-month supply recommended — ibuprofen is most-used NSAID in lunar environment for musculoskeletal conditions.',
                'supply_chain_notes' => 'WHO Essential Medicine. High consumption expected in mining and construction populations. Anti-inflammatory properties make it valuable for EVA-related musculoskeletal injury. Monitor and maintain 12-month minimum stock.',
                'interactions'       => 'Warfarin, aspirin, lithium, diuretics, ACE inhibitors. Avoid concurrent NSAID use.',
                'contraindications'  => 'GI ulceration, renal impairment, cardiovascular disease, third trimester pregnancy.',
                'side_effects'       => 'GI upset, peptic ulcer, renal impairment, cardiovascular effects with long-term use.',
                'alternatives'       => 'Diclofenac, naproxen, acetaminophen, celecoxib (COX-2 selective, lower GI risk).',
                'who_essential'      => true,
                'lunar_critical'     => true,
                'search_keywords'    => 'pain, inflammation, NSAID, Advil, ibuprofen, fever, muscle pain',
            ],
            [
                'generic_name'       => 'Morphine',
                'brand_names'        => 'MS Contin, MSIR',
                'drug_class'         => 'Opioid Analgesic',
                'mechanism'          => 'Mu-opioid receptor agonist in CNS and peripheral tissues. Reduces perception of pain and emotional response to pain.',
                'indications'        => 'Severe pain, including post-operative pain, trauma, cancer pain, and acute MI. Essential for lunar medical bay crash kit and surgical support.',
                'dosing_standard'    => 'IV: 0.1 mg/kg every 3-4h. PO: 10-30 mg every 4h.',
                'dosing_lunar'       => 'Pharmacokinetics likely unchanged in 1/6g. Reduced doses may be effective due to CNS sensitization from radiation and stress — start at 50-75% of standard dose and titrate. Nausea particularly bothersome in low-g environment (vomiting management critical). Respiratory monitoring mandatory.',
                'storage_standard'   => 'Controlled substance storage. Room temperature.',
                'storage_lunar'      => 'Controlled access storage — biometric lock recommended. Radiation-shielded. Maintain 90-day supply minimum. Strict inventory with dual verification. All uses documented in patient record. Radiation slightly degrades solution form — prefer lyophilized powder form for lunar storage.',
                'supply_chain_notes' => 'Controlled substance. Supply chain requires special authorization. Maintain strict inventory. Consider on-demand vs. standing supply model. Emergency request protocol for resupply if stock runs low.',
                'interactions'       => 'MAOIs (severe reaction). CNS depressants. Anticholinergics. Alcohol.',
                'contraindications'  => 'Severe respiratory depression, acute asthma, paralytic ileus. Caution in head injury (masks neurological exam).',
                'side_effects'       => 'Nausea, constipation, sedation, respiratory depression, dependence.',
                'alternatives'       => 'Fentanyl (more potent, shorter acting, useful for procedures), tramadol (lower dependence potential for moderate pain), ketorolac (non-opioid for acute severe pain).',
                'who_essential'      => true,
                'lunar_critical'     => true,
                'search_keywords'    => 'pain, opioid, severe pain, morphine, narcotic, post-operative',
            ],
            // Cardiovascular
            [
                'generic_name'       => 'Amlodipine',
                'brand_names'        => 'Norvasc',
                'drug_class'         => 'Calcium Channel Blocker (Dihydropyridine)',
                'mechanism'          => 'Blocks L-type calcium channels in vascular smooth muscle, causing vasodilation and reduced peripheral vascular resistance.',
                'indications'        => 'Hypertension, angina. First-line antihypertensive for lunar residents — calcium channel blockers do not worsen exercise deconditioning, unlike beta-blockers, making them particularly suitable.',
                'dosing_standard'    => '5-10 mg once daily.',
                'dosing_lunar'       => 'Start at 5mg. Assess blood pressure response in context of lunar environment. Peripheral edema may be masked by fluid redistribution patterns in 1/6g — monitor by weight and clinical exam rather than extremity edema. Long half-life (30-50h) makes once-daily dosing reliable even with irregular sleep schedules.',
                'storage_standard'   => 'Room temperature.',
                'storage_lunar'      => 'Stable at 15-25°C. Protect from light. Radiation-shielded storage. Long expiry (3-4 years) — can maintain 24-month supply. Critical for cardiovascular management in lunar population.',
                'supply_chain_notes' => 'Generic widely available. Highly stable. 24-month stock recommended given cardiovascular disease prevalence in lunar population. Can be co-packaged with lisinopril for combination hypertension management.',
                'interactions'       => 'Simvastatin (increased simvastatin levels), grapefruit juice, cyclosporine.',
                'contraindications'  => 'Severe hypotension, severe aortic stenosis, unstable angina.',
                'side_effects'       => 'Peripheral edema, flushing, headache, dizziness.',
                'alternatives'       => 'Lisinopril (ACE inhibitor), losartan (ARB), nifedipine (shorter-acting CCB).',
                'who_essential'      => false,
                'lunar_critical'     => true,
                'search_keywords'    => 'blood pressure, hypertension, heart, calcium channel, antihypertensive',
            ],
            [
                'generic_name'       => 'Enoxaparin',
                'brand_names'        => 'Lovenox, Clexane',
                'drug_class'         => 'Low Molecular Weight Heparin (Anticoagulant)',
                'mechanism'          => 'Inhibits Factor Xa and thrombin via antithrombin III. More predictable pharmacokinetics than unfractionated heparin.',
                'indications'        => 'DVT treatment and prophylaxis, pulmonary embolism, acute coronary syndromes. High-priority medication for lunar medical bay given elevated thrombosis risk in residents.',
                'dosing_standard'    => 'Treatment: 1 mg/kg SC every 12 hours. Prophylaxis: 40 mg SC once daily.',
                'dosing_lunar'       => 'Weight-based dosing unchanged. Monitor anti-Xa levels if renal function impaired. SC injection technique: in 1/6g, subcutaneous tissue may have altered perfusion — abdominal injection preferred over thigh for more consistent absorption. Storage quality monitoring critical — degraded LMWH may be less effective without visual indication.',
                'storage_standard'   => 'Refrigerated (2-8°C) or room temperature up to 25°C for 1 month.',
                'storage_lunar'      => 'Maintain cold chain (habitat refrigerator). Radiation shielding critical — ionizing radiation degrades LMWH. Monitor storage temperature continuously with alarm. 6-month supply minimum. Emergency plan for refrigeration failure (move to shielded room-temperature storage; maximum 1-month effective use).',
                'supply_chain_notes' => 'Requires cold chain. High priority for resupply planning. Biological product requiring careful handling. Single-dose pre-filled syringes preferred for lunar use — reduce dosing errors and infection risk.',
                'interactions'       => 'Aspirin, NSAIDs, other anticoagulants, SSRIs (increased bleeding risk).',
                'contraindications'  => 'Active bleeding, heparin-induced thrombocytopenia, severe renal failure (dose adjust).',
                'side_effects'       => 'Bleeding, thrombocytopenia (HIT), injection site reactions.',
                'alternatives'       => 'Unfractionated heparin (if anti-Xa monitoring unavailable), rivaroxaban (oral, no monitoring but less reversal options).',
                'who_essential'      => false,
                'lunar_critical'     => true,
                'search_keywords'    => 'blood clot, anticoagulant, heparin, DVT, thrombosis, LMWH',
            ],
            // Respiratory
            [
                'generic_name'       => 'Salbutamol (Albuterol)',
                'brand_names'        => 'Ventolin, ProAir',
                'drug_class'         => 'Short-Acting Beta-2 Agonist (SABA) Bronchodilator',
                'mechanism'          => 'Selectively stimulates beta-2 adrenergic receptors in bronchial smooth muscle, causing bronchodilation.',
                'indications'        => 'Acute bronchospasm, asthma, COPD exacerbation, exercise-induced bronchospasm. Also useful for regolith dust bronchospasm in exposed workers.',
                'dosing_standard'    => 'Inhaler: 1-2 puffs (100-200 mcg) every 4-6h. Nebulization: 2.5-5 mg in 3 mL NS every 4-8h.',
                'dosing_lunar'       => 'Metered-dose inhaler function in 1/6g requires special consideration — propellant delivery may be altered. Nebulizer is preferred in lunar medical bay context. Inhaler spacer device recommended for all residents. Regolith-exposed workers: prophylactic use before decontamination shower in severe exposure.',
                'storage_standard'   => 'Room temperature, protect from heat and direct sunlight.',
                'storage_lunar'      => 'Store at 15-25°C. Pressurized MDI canister sensitive to temperature extremes — avoid near habitat wall (temperature variation) or near heating elements. Radiation shielding. 24-month supply. Nebulizer solution more stable than MDI in lunar storage conditions.',
                'supply_chain_notes' => 'WHO Essential Medicine. Very high consumption expected in regolith-exposed workforce. Stock both MDI and nebulizer solution forms. Nebulizer machine requires maintenance — include in medical equipment maintenance schedule.',
                'interactions'       => 'Beta-blockers (antagonism). Diuretics (hypokalemia risk). MAOIs and tricyclics (cardiovascular effects).',
                'contraindications'  => 'Known hypersensitivity. Use with caution in severe cardiac disease.',
                'side_effects'       => 'Tremor, tachycardia, hypokalemia, headache.',
                'alternatives'       => 'Ipratropium (anticholinergic, good combination with salbutamol), terbutaline (alternative SABA).',
                'who_essential'      => true,
                'lunar_critical'     => true,
                'search_keywords'    => 'asthma, breathing, bronchodilator, inhaler, respiratory, dust, wheeze',
            ],
            // Antibiotics
            [
                'generic_name'       => 'Amoxicillin',
                'brand_names'        => 'Amoxil, Trimox',
                'drug_class'         => 'Beta-Lactam Antibiotic (Aminopenicillin)',
                'mechanism'          => 'Inhibits bacterial cell wall synthesis by binding to penicillin-binding proteins (PBPs). Bactericidal.',
                'indications'        => 'Community-acquired pneumonia, upper respiratory tract infections, urinary tract infections, skin and soft tissue infections, dental infections. Broad-spectrum coverage for most community-acquired infections in lunar habitat.',
                'dosing_standard'    => '250-500 mg three times daily, or 875 mg twice daily.',
                'dosing_lunar'       => 'Standard dosing. Oral bioavailability not significantly affected by lunar gravity. In severe infection, IV ampicillin preferred (if IV access established). Duration of therapy per standard guidelines — avoid over-treating due to antibiotic stewardship in closed habitat (resistance emergence would be catastrophic).',
                'storage_standard'   => 'Capsules/tablets: room temperature. Suspension: refrigerate after reconstitution.',
                'storage_lunar'      => 'Capsule/tablet form only for lunar storage — suspension requires refrigeration and has short shelf life. Store at 15-25°C, radiation shielded. Long shelf life (4-5 years for tablets). Maintain 12-month supply. Antibiotic stewardship program essential in lunar habitat.',
                'supply_chain_notes' => 'WHO Essential Medicine. Broad-spectrum first-line antibiotic. Maintain comprehensive antibiotic formulary including penicillins, cephalosporins, fluoroquinolones, and macrolides. Antibiotic resistance monitoring critical in closed habitat.',
                'interactions'       => 'Methotrexate, warfarin, allopurinol. Reduces efficacy of oral contraceptives (relevant for lunar reproductive health).',
                'contraindications'  => 'Penicillin hypersensitivity (cross-reactivity with other beta-lactams).',
                'side_effects'       => 'GI upset, diarrhea, rash, allergic reactions.',
                'alternatives'       => 'Doxycycline (penicillin allergy), azithromycin (atypical coverage), cephalexin.',
                'who_essential'      => true,
                'lunar_critical'     => true,
                'search_keywords'    => 'antibiotic, infection, bacteria, pneumonia, penicillin, amoxicillin',
            ],
            [
                'generic_name'       => 'Ciprofloxacin',
                'brand_names'        => 'Cipro, Ciproxin',
                'drug_class'         => 'Fluoroquinolone Antibiotic',
                'mechanism'          => 'Inhibits bacterial DNA gyrase and topoisomerase IV, preventing DNA replication. Broad-spectrum bactericidal.',
                'indications'        => 'Urinary tract infections, respiratory infections (non-first line), GI infections, skin infections, anthrax prophylaxis. Particularly valuable for Gram-negative infection coverage in lunar habitat.',
                'dosing_standard'    => '250-750 mg twice daily (oral) or 200-400 mg IV every 12 hours.',
                'dosing_lunar'       => 'Standard dosing. Important lunar consideration: fluoroquinolones may worsen tendon injury risk — already elevated in musculoskeletal-challenged lunar residents. Avoid in residents with active tendinopathy. Adequate hydration essential (renal stone risk in dehydrated lunar residents).',
                'storage_standard'   => 'Room temperature.',
                'storage_lunar'      => 'Excellent stability at room temperature. Radiation shielded. 3-year expiry allows larger stock. Both oral and IV forms in lunar medical bay. IV form for critically ill patients where oral route not available.',
                'supply_chain_notes' => 'Essential broad-spectrum antibiotic. Alternative to amoxicillin for Gram-negative coverage. Reserve IV form for in-hospital use. Antibiotic stewardship: do not use for minor infections when narrower spectrum appropriate.',
                'interactions'       => 'Antacids, calcium, iron (reduce absorption). Warfarin (increases INR). Methotrexate. QT-prolonging drugs.',
                'contraindications'  => 'Known hypersensitivity to fluoroquinolones. Avoid in children <18 (cartilage effects). History of fluoroquinolone-induced tendinopathy.',
                'side_effects'       => 'GI upset, CNS effects (insomnia, dizziness), tendon rupture, QT prolongation, photosensitivity.',
                'alternatives'       => 'Trimethoprim-sulfamethoxazole (UTI), levofloxacin (respiratory), ceftriaxone (severe infections).',
                'who_essential'      => true,
                'lunar_critical'     => true,
                'search_keywords'    => 'antibiotic, UTI, infection, fluoroquinolone, ciprofloxacin, bacteria',
            ],
            // Mental health
            [
                'generic_name'       => 'Escitalopram',
                'brand_names'        => 'Lexapro, Cipralex',
                'drug_class'         => 'SSRI Antidepressant',
                'mechanism'          => 'Selectively inhibits serotonin reuptake, increasing serotonergic neurotransmission.',
                'indications'        => 'Major depressive disorder, generalized anxiety disorder, panic disorder, PTSD. First-line for Earth-sickness syndrome with depressive features, isolation syndrome, and circadian disruption-related mood disorders in lunar residents.',
                'dosing_standard'    => '10-20 mg once daily.',
                'dosing_lunar'       => 'Start at 10mg. Response assessment at 4-6 weeks. In lunar residents, higher rates of concurrent sleep disruption may require concurrent sleep-focused interventions. Monitor for QT prolongation (baseline ECG). Weight gain side effect particularly concerning given lunar nutrition challenges. Sexual side effects may worsen interpersonal relationships in confined habitats — discuss before starting.',
                'storage_standard'   => 'Room temperature, protect from moisture.',
                'storage_lunar'      => 'Stable at 15-25°C. Radiation shielded. 3-year expiry — maintain 12-month supply. High demand expected in lunar mental health population. Tablet form stable.',
                'supply_chain_notes' => 'High demand medication. Mental health conditions underreported in early lunar population — actual need may exceed estimates. Stock SSRIs generously. Sertraline is an acceptable alternative with equivalent efficacy.',
                'interactions'       => 'MAOIs (serotonin syndrome — potentially fatal), QT-prolonging drugs, anticoagulants.',
                'contraindications'  => 'Concurrent MAOI use, long QT syndrome.',
                'side_effects'       => 'Nausea, insomnia, sexual dysfunction, weight gain, QT prolongation.',
                'alternatives'       => 'Sertraline (similar efficacy), mirtazapine (also helps sleep), bupropion (no sexual side effects, helps with motivation).',
                'who_essential'      => true,
                'lunar_critical'     => true,
                'search_keywords'    => 'depression, antidepressant, SSRI, anxiety, Earth-sickness, mood, mental health',
            ],
            // Emergency medications
            [
                'generic_name'       => 'Epinephrine (Adrenaline)',
                'brand_names'        => 'EpiPen, Adrenalin',
                'drug_class'         => 'Sympathomimetic / Alpha and Beta Adrenergic Agonist',
                'mechanism'          => 'Stimulates alpha-1 (vasoconstriction), beta-1 (cardiac stimulation), and beta-2 (bronchodilation) adrenergic receptors.',
                'indications'        => 'Anaphylaxis (life-saving), cardiac arrest (ACLS protocol), severe bronchospasm.',
                'dosing_standard'    => 'Anaphylaxis: 0.3-0.5 mg IM (anterolateral thigh). Cardiac arrest: 1 mg IV every 3-5 minutes.',
                'dosing_lunar'       => 'IM injection in 1/6g: anterolateral thigh or deltoid both effective. Absorption rate may differ from Earth due to altered muscle perfusion — monitor response carefully. In cardiac arrest, CPR technique in 1/6g requires modification (body strapping for compressions). Dose unchanged — cardiac physiology is unchanged.',
                'storage_standard'   => 'Room temperature, protect from light.',
                'storage_lunar'      => 'CRITICAL STOCK. Multiple pre-filled syringes in crash kit plus EpiPen auto-injectors for first responders. Protect from temperature extremes and light (degradation to inactive adrenochrome occurs with heat/light). Radiation shielded. Replace every 12-18 months — stability critical. Check solution color: pink = degraded, replace immediately.',
                'supply_chain_notes' => 'Absolutely essential. Non-negotiable stock item. All habitats and EVA first-aid kits must contain epinephrine. Anaphylaxis to food, stings, or medications in a lunar habitat without epinephrine is a preventable death.',
                'interactions'       => 'Beta-blockers (may antagonize), tricyclics (enhanced cardiovascular effects), MAOIs.',
                'contraindications'  => 'No absolute contraindications in anaphylaxis or cardiac arrest. Relative: hyperthyroidism, severe hypertension, cardiac arrhythmia.',
                'side_effects'       => 'Tachycardia, hypertension, anxiety, tremor, arrhythmia.',
                'alternatives'       => 'No equivalent for anaphylaxis. Vasopressin alternative in cardiac arrest.',
                'who_essential'      => true,
                'lunar_critical'     => true,
                'search_keywords'    => 'anaphylaxis, allergic reaction, emergency, adrenaline, cardiac arrest, EpiPen',
            ],
            // Vitamins and supplements
            [
                'generic_name'       => 'Vitamin D3 (Cholecalciferol)',
                'brand_names'        => 'Various',
                'drug_class'         => 'Fat-Soluble Vitamin / Hormone Precursor',
                'mechanism'          => 'Converted to active form 1,25-dihydroxyvitamin D (calcitriol), which regulates calcium absorption, bone metabolism, immune function, and has pleiotropic effects on many tissues.',
                'indications'        => 'Deficiency prevention and treatment (universal in lunar residents without supplementation), osteoporosis prevention, immune support, mood support.',
                'dosing_standard'    => 'Maintenance: 1000-4000 IU daily. Deficiency treatment: 50,000 IU weekly for 8 weeks.',
                'dosing_lunar'       => 'Universal supplementation for all lunar residents: minimum 2000 IU daily. First 3 months of residence: 4000 IU daily (loading). Quarterly monitoring of 25-OH vitamin D levels — target 75-125 nmol/L. In residents with concurrent bone loss: up to 6000 IU daily (combined with calcium). Magnesium supplementation required for optimal vitamin D activation.',
                'storage_standard'   => 'Room temperature, protect from light.',
                'storage_lunar'      => 'Very stable at room temperature. Protect from light and temperature extremes. 2-year expiry for soft gel capsules. Tablet form slightly more stable. Radiation shielded. Maintain 24-month supply for entire habitat population — this is prophylactic medication for every resident.',
                'supply_chain_notes' => 'Must be stocked for EVERY resident. Universal supplementation is mandatory policy. Bulk procurement for colony-wide distribution. Cost-effective — one of the most important disease prevention medications in lunar formulary.',
                'interactions'       => 'Thiazide diuretics (hypercalcemia risk). Digoxin (toxicity with hypercalcemia). Certain anticonvulsants reduce efficacy.',
                'contraindications'  => 'Hypercalcemia, hypervitaminosis D. Monitor calcium levels with high-dose therapy.',
                'side_effects'       => 'Toxicity with excessive doses: hypercalcemia, hypercalciuria, renal stones. Rare at recommended doses.',
                'alternatives'       => 'Ergocalciferol (D2) — less potent, less preferred. UV light therapy lamps (adjunct, not replacement).',
                'who_essential'      => false,
                'lunar_critical'     => true,
                'search_keywords'    => 'vitamin D, bone, supplement, deficiency, calcium, immune, sunlight',
            ],
            // Sleeping and circadian
            [
                'generic_name'       => 'Melatonin',
                'brand_names'        => 'Various OTC brands',
                'drug_class'         => 'Chronobiotic / Sleep Regulator',
                'mechanism'          => 'Endogenous hormone produced by pineal gland at night; exogenous melatonin shifts and reinforces circadian rhythm phase. Also direct hypnotic effect at higher doses.',
                'indications'        => 'Circadian rhythm disorders (primary indication in lunar residents), jet lag equivalent from long transit, insomnia with circadian component. One of the most important medications for lunar resident wellbeing.',
                'dosing_standard'    => 'Circadian phase shifting: 0.5-3 mg, timed 2 hours before desired sleep time. Hypnotic effect: 1-5 mg at bedtime.',
                'dosing_lunar'       => 'Low-dose (0.5-1 mg) for circadian entrainment — more effective than high dose for phase shifting. Timing critical: administer at consistent clock time corresponding to habitat "dusk" lighting. 14-day lunar day cycle requires protocol adaptation. Combine with circadian lighting protocol for maximum effect. Long-term use appears safe — no evidence of tolerance or dependence.',
                'storage_standard'   => 'Room temperature, protect from light.',
                'storage_lunar'      => 'Very stable. Radiation shielded (light sensitive compound). 2-year expiry. Maintain large supply — this is a first-line intervention for circadian problems affecting most residents. Immediate-release tablets preferred for circadian use; extended-release for maintenance insomnia.',
                'supply_chain_notes' => 'Available OTC. Inexpensive. Stock generously — high usage expected. Universal prophylactic use in first month of lunar residence may improve adaptation outcomes.',
                'interactions'       => 'CNS depressants (additive sedation). Anticoagulants (may increase INR). Fluvoxamine (dramatically increases melatonin levels).',
                'contraindications'  => 'Autoimmune conditions (theoretical concern). Pregnancy (insufficient data).',
                'side_effects'       => 'Daytime drowsiness if poorly timed. Vivid dreams. Generally excellent tolerability.',
                'alternatives'       => 'Ramelteon (melatonin receptor agonist, prescription), zolpidem (hypnotic, but habit-forming), cognitive behavioral therapy for insomnia (CBT-I, first-line non-pharmacological).',
                'who_essential'      => false,
                'lunar_critical'     => true,
                'search_keywords'    => "sleep, insomnia, circadian, melatonin, can't sleep, light cycle, shift work",
            ],
        ];
    }

    private function generateVariants(array $base): array
    {
        $variants = [];
        $i = 0;

        $dosageModifiers = [
            'Extended-Release', 'IV Formulation', 'Topical', 'Pediatric', 'Low-Dose',
            'High-Dose', 'Combination', 'Sustained-Release', 'Sublingual', 'Nasal',
        ];
        $classExtensions = [
            'Second Generation', 'Alternative', 'Analogue', 'Generic Formulation',
            'Biosimilar', 'Modified Release', 'Enteric-Coated', 'Ophthalmic',
        ];

        foreach ($dosageModifiers as $modIdx => $mod) {
            foreach ($base as $baseItem) {
                $ext = $classExtensions[$i % count($classExtensions)];
                $baseName = str_replace(
                    array_map(fn($m) => "$m ", $dosageModifiers),
                    '',
                    $baseItem['generic_name']
                );
                $variants[] = [
                    'generic_name'       => "{$mod} {$baseName}",
                    'brand_names'        => null,
                    'drug_class'         => "{$baseItem['drug_class']} — {$ext}",
                    'mechanism'          => $baseItem['mechanism'],
                    'indications'        => "{$mod} formulation. " . $baseItem['indications'],
                    'dosing_standard'    => "See {$mod} prescribing information. " . Str::limit($baseItem['dosing_standard'], 200),
                    'dosing_lunar'       => $baseItem['dosing_lunar'] ?? null,
                    'storage_standard'   => $baseItem['storage_standard'] ?? null,
                    'storage_lunar'      => $baseItem['storage_lunar'] ?? null,
                    'supply_chain_notes' => $baseItem['supply_chain_notes'] ?? null,
                    'interactions'       => $baseItem['interactions'] ?? null,
                    'contraindications'  => $baseItem['contraindications'] ?? null,
                    'side_effects'       => $baseItem['side_effects'] ?? null,
                    'alternatives'       => $baseItem['alternatives'] ?? null,
                    'who_essential'      => false,
                    'lunar_critical'     => $baseItem['lunar_critical'] ?? false,
                    'search_keywords'    => ($baseItem['search_keywords'] ?? '') . ", {$mod}, formulation",
                ];
                $i++;
            }
        }

        return $variants;
    }
}
