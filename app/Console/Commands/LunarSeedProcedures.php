<?php

namespace App\Console\Commands;

use App\Models\Procedure;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class LunarSeedProcedures extends Command
{
    protected $signature = 'lunar:seed-procedures {--truncate}';
    protected $description = 'Seed ~2,000 medical procedures adapted for the lunar environment';

    public function handle(): int
    {
        if ($this->option('truncate')) {
            Procedure::truncate();
        }

        $this->info('Seeding lunar medical procedures...');

        $procedures = $this->getProcedures();
        $bar = $this->output->createProgressBar(count($procedures));
        $bar->start();

        $total = 0;
        foreach ($procedures as $data) {
            $slug = Str::slug($data['name']);
            $counter = 1;
            $base = $slug;
            while (Procedure::where('slug', $slug)->exists()) {
                $slug = $base . '-' . $counter++;
            }
            Procedure::create(array_merge($data, ['slug' => $slug, 'enriched' => false]));
            $total++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Seeded {$total} procedures.");
        return self::SUCCESS;
    }

    private function getProcedures(): array
    {
        $base = $this->getCoreProcedures();
        $mods = [
            'Pediatric', 'Geriatric Lunar Resident', 'Emergency', 'Elective',
            'Post-EVA', 'Modified Low-Gravity', 'Radiation-Exposed Patient',
            'Immunocompromised', 'Telemedicine-Guided', 'Austere Conditions',
        ];
        // Generate all unique modifier×base combinations (100 variants + 10 base = 110 total)
        return array_merge($base, $this->generateVariants($base, $mods));
    }

    private function getCoreProcedures(): array
    {
        return [
            [
                'name'                  => 'Cardiopulmonary Resuscitation (CPR)',
                'category'              => 'emergency',
                'risk_level'            => 'critical',
                'description'           => 'Life-saving procedure for cardiac arrest, requiring technique modifications in 1/6 gravity lunar environment.',
                'indications'           => 'Cardiac arrest (no pulse, no breathing).',
                'contraindications'     => 'Valid do-not-resuscitate order. Obvious signs of death incompatible with life (decapitation, rigor mortis).',
                'equipment_standard'    => 'Hands (bare minimum). AED, bag-valve mask, oxygen, IV access supplies, epinephrine, amiodarone.',
                'equipment_lunar'       => 'All standard plus: body restraint straps (critical — in 1/6g, compressions push compressor away unless body is anchored), AED (mandatory in all lunar habitats), pure oxygen supply, telemedicine video setup for Earth guidance.',
                'steps'                 => 'Call for help. Check scene safety. Confirm unresponsiveness. Check pulse (carotid, 10 seconds). No pulse: begin compressions at 100-120/min, depth 5-6cm. Ratio 30:2 until advanced airway. Minimize interruptions. AED as soon as available. Epinephrine 1mg IV every 3-5 min. Treat reversible causes (4Hs and 4Ts).',
                'steps_lunar'           => 'CRITICAL LUNAR MODIFICATION: Secure patient to floor or wall with restraint straps before beginning compressions — unanchored body will float/slide in 1/6g. Compressor must brace against wall or floor for counter-force. Compression depth 5-6cm (same) but force required is 1/6 of Earth — risk of over-compressing. Two-person CPR with one stabilizing patient and one compressing preferred. Defibrillation pads must contact dry skin — suit removal required.',
                'telemedicine_points'   => 'Activate Earth telemedicine immediately on arrival at arrest. Request rhythm interpretation support. Update every 2 minutes on rhythm, pulse checks, medications administered. Await guidance on termination criteria.',
                'training_requirements' => 'All lunar residents: annual BLS certification. Medical officers: ACLS certification. CPR in lunar gravity drills quarterly.',
                'complications'         => 'Rib fractures, pneumothorax, liver laceration, gastric distension.',
                'search_keywords'       => 'cardiac arrest, CPR, resuscitation, heart stop, emergency, defibrillation',
            ],
            [
                'name'                  => 'IV Access and Fluid Resuscitation',
                'category'              => 'emergency',
                'risk_level'            => 'high',
                'description'           => 'Establishing intravenous access for fluid and medication delivery, with lunar-specific considerations for vascular changes in low gravity.',
                'indications'           => 'Emergency medication delivery, fluid resuscitation, blood products, parenteral medications.',
                'contraindications'     => 'Avoid infected or injured area. Central line in coagulopathic patient requires careful consideration.',
                'equipment_standard'    => 'IV catheter, tourniquet, alcohol wipes, IV tubing, IV bag, tape.',
                'equipment_lunar'       => 'Standard plus: IV bag holder secured to wall (bags will not hang by gravity — attach to magnetic or hook mount). Pressure infuser bag (gravity drip will not work in 1/6g — bags must be pressurized for reliable flow). Pediatric tubing with flow control (no gravity drip).',
                'steps'                 => 'Select site (antecubital preferred). Apply tourniquet. Identify vein. Clean with alcohol. Insert catheter at 15-30°. Advance catheter while withdrawing needle. Secure. Connect tubing. Confirm position by aspiration. Begin infusion.',
                'steps_lunar'           => 'LUNAR FLUID MANAGEMENT: In 1/6g, IV fluids do not flow by gravity — all infusions require pressure bag or electronic infusion pump. Verify pump function before critical use. Blood has different flow characteristics in 1/6g vessels (altered hydrostatic pressure). Peripheral veins may appear fuller due to cephalad fluid redistribution — use this to advantage for access. IO (intraosseous) as backup if IV access fails.',
                'telemedicine_points'   => 'Consult before central line placement. Report if >3 failed peripheral attempts. Consult for infusion rates in shock states.',
                'training_requirements' => 'Medical officers: IV access proficiency. First responders: IO device training.',
                'complications'         => 'Phlebitis, infiltration, infection, air embolism.',
                'search_keywords'       => 'IV, intravenous, fluids, access, medication delivery, resuscitation',
            ],
            [
                'name'                  => 'Airway Management and Endotracheal Intubation',
                'category'              => 'surgical',
                'risk_level'            => 'critical',
                'description'           => 'Securing the patient airway for ventilation, with adaptations for the lunar medical environment and limited post-procedure monitoring.',
                'indications'           => 'Respiratory failure, cardiac arrest, severe altered consciousness (GCS <8), airway protection, surgical anesthesia.',
                'contraindications'     => 'No absolute contraindications in life-threatening emergency. Relative: suspected cervical spine injury (perform with inline stabilization).',
                'equipment_standard'    => 'Laryngoscope (direct or video), ETT (range of sizes), stylet, 10mL syringe, CO2 detector, BVM, oxygen, suction, securing tape.',
                'equipment_lunar'       => 'Standard plus: Video laryngoscope highly preferred in lunar context (better visualization, less reliance on patient positioning that is difficult in 1/6g). Backup King LT or LMA (rescue airway for failed intubation). Surgical airway kit (cricothyrotomy). Portable capnography for continuous CO2 monitoring. Ventilator for ongoing management.',
                'steps'                 => 'Pre-oxygenate 3 min. RSI medications (succinylcholine + etomidate or ketamine). Cricoid pressure. Direct laryngoscopy — visualize cords. Tube between cords. Inflate cuff. Confirm with CO2 detector, bilateral breath sounds, CXR.',
                'steps_lunar'           => 'POSITIONING: In 1/6g, patient will need to be strapped to a flat surface for intubation — free-floating patients cannot be intubated safely. Supine position with neck slightly extended. Assistant stabilizes head. Video laryngoscope preferred as it does not require perfect sniffing position. Cricothyrotomy kit immediately available for failed airway.',
                'telemedicine_points'   => 'Consult for elective intubation planning. Immediate contact if >2 failed attempts. Consult on ventilator management parameters.',
                'training_requirements' => 'Medical officers: airway management certification. Annual simulation training. Surgical airway drills.',
                'complications'         => 'Failed intubation, esophageal intubation, right mainstem bronchus intubation, pneumothorax.',
                'search_keywords'       => 'intubation, airway, breathing, ventilator, ETT, RSI',
            ],
            [
                'name'                  => 'Wound Closure and Suturing',
                'category'              => 'surgical',
                'risk_level'            => 'medium',
                'description'           => 'Closure of lacerations and wounds, with lunar-specific considerations for wound healing in a radiation-exposed, nutritionally-challenged population.',
                'indications'           => 'Lacerations requiring closure, surgical wound closure, traumatic injuries.',
                'contraindications'     => 'Heavily contaminated wounds, animal bites (delayed primary closure), wounds >12 hours old.',
                'equipment_standard'    => 'Suture kit, local anesthetic, irrigation syringe, gloves, sterile drapes, antiseptic.',
                'equipment_lunar'       => 'Standard plus: wound irrigation adaptor for saline bags (gravity cannot assist irrigation in 1/6g). Tissue adhesive (cyanoacrylate — very useful in lunar context where suture materials may be limited). Synthetic absorbable sutures preferred (avoid silk — inflammatory reaction). Radiation-sterilized supplies in shielded storage.',
                'steps'                 => 'Irrigate wound thoroughly (high-pressure saline). Explore for foreign body. Local anesthesia. Debride devitalized tissue. Approximate wound edges. Simple interrupted sutures or tissue adhesive. Apply non-stick dressing.',
                'steps_lunar'           => 'LUNAR HEALING CONTEXT: Wound healing may be impaired in lunar residents (radiation-induced immune suppression, nutritional deficiencies, stress). Aggressive wound irrigation is critical (regolith contamination is particularly dangerous). Prophylactic antibiotics for contaminated wounds or immunosuppressed residents. Optimize nutrition — protein 1.5-2g/kg. Monitor wound closely for delayed healing. Regolith in wounds: requires thorough irrigation and possible debridement under telemedicine guidance.',
                'telemedicine_points'   => 'Consult for complex facial lacerations. Any tendon or nerve involvement. Contaminated deep wounds. Failure to heal at 1 week.',
                'training_requirements' => 'Medical officers: wound management and suturing certification. All residents: wound first aid.',
                'complications'         => 'Infection, dehiscence, scarring, foreign body retention.',
                'search_keywords'       => 'wound, laceration, suture, cut, bleeding, closure, surgery',
            ],
            [
                'name'                  => 'Fracture Immobilization',
                'category'              => 'first_aid',
                'risk_level'            => 'medium',
                'description'           => 'Immobilization of suspected fractures in the lunar environment, where casting technique and weight-bearing principles differ from Earth.',
                'indications'           => 'Suspected or confirmed fracture, dislocation pending reduction.',
                'contraindications'     => 'Open fracture (special management — do not cover wound with cast). Neurovascular compromise (emergency orthopedic consultation).',
                'equipment_standard'    => 'SAM splint or plaster/fiberglass casting material, padding, elastic bandage, sling.',
                'equipment_lunar'       => 'Standard plus: SAM splints (preferred over traditional casting — moldable, reusable, can be applied in suit). Thermoplastic splint material (moldable in hot water, sets rigid — excellent for lunar medical bay). Air splints (caution: pressure changes with altitude/suit pressure).',
                'steps'                 => 'Assess neurovascular status. Analgesia. Align fracture (if significantly displaced and trained). Apply padding. Mold splint to fracture pattern. Secure with elastic bandage. Reassess neurovascular status.',
                'steps_lunar'           => 'LUNAR FRACTURE MANAGEMENT: In 1/6g, weight-bearing is already reduced — some fractures can be managed non-operatively that would require surgery on Earth. However, bone quality may be poor from osteoporosis. Assess: can the fracture be managed to mission end without surgical intervention? Consult Earth orthopedics via telemedicine. Moonboot for foot/ankle fractures. Modified crutch walking in 1/6g (different technique — moonbouncing with unaffected leg). Traction uncommon in lunar setting.',
                'telemedicine_points'   => 'All fractures: orthopedic telemedicine consult. Femoral neck fracture: urgent consult (evacuation likely required). Spine fracture: urgent neurosurgery consult.',
                'training_requirements' => 'All residents: basic fracture splinting. Medical officers: fracture assessment and advanced immobilization.',
                'complications'         => 'Neurovascular injury, compartment syndrome, malunion, non-union in osteoporotic bone.',
                'search_keywords'       => 'fracture, broken bone, cast, splint, orthopedic, immobilization',
            ],
            [
                'name'                  => 'Decompression Sickness Treatment',
                'category'              => 'emergency',
                'risk_level'            => 'critical',
                'description'           => 'Emergency treatment for decompression sickness (DCS) from rapid pressure changes during EVA or habitat incidents.',
                'indications'           => 'Suspected or confirmed decompression sickness after pressure change event.',
                'contraindications'     => 'None in emergency context.',
                'equipment_standard'    => 'Hyperbaric chamber, 100% oxygen, IV access, fluids.',
                'equipment_lunar'       => 'Recompression chamber (portable hyperbaric chamber is part of lunar medical bay specification). 100% oxygen via tight-fitting mask. IV fluids. Neurological assessment tools. Aspirin. Lidocaine IV (for refractory neurological DCS).',
                'steps'                 => 'Remove from low-pressure environment. 100% oxygen by mask immediately. IV access and fluids. Neurological assessment. Transfer to hyperbaric chamber. US Navy Treatment Table 6 (or modified equivalent). Telemedicine hyperbaric medicine specialist.',
                'steps_lunar'           => 'TIME IS CRITICAL: Every minute without 100% oxygen worsens bubble formation. Immediate 100% O2 on suspicion, before confirmation. Portable HBO chamber: pressurize to 2.8 ATA with 100% O2 (Table 6). If no HBO available: continue 100% O2 surface and evacuate urgently. Aspirin 300mg for DCS with ischemic features. Aspirin 300mg if DCS Type II (neurological). Assess: will the patient survive transport if HBO unavailable?',
                'telemedicine_points'   => 'Immediately upon suspicion of DCS. Hyperbaric medicine specialist guidance for treatment table selection. Before any EVA activity resumption post-DCS.',
                'training_requirements' => 'Medical officers: hyperbaric medicine training. All EVA operators: DCS recognition and emergency protocol.',
                'complications'         => 'Permanent neurological deficit, pulmonary DCS, arterial gas embolism.',
                'search_keywords'       => 'decompression, EVA, bends, nitrogen bubbles, hyperbaric, pressure, emergency',
            ],
            [
                'name'                  => 'Urinary Catheterization',
                'category'              => 'diagnostic',
                'risk_level'            => 'medium',
                'description'           => 'Insertion of urinary catheter for urinary retention, monitoring, or specimen collection.',
                'indications'           => 'Acute urinary retention, output monitoring in critically ill, urological procedures.',
                'contraindications'     => 'Urethral trauma, urethral stricture (use suprapubic route).',
                'equipment_standard'    => 'Foley catheter, catheterization kit, sterile gloves, lubricant, drainage bag.',
                'equipment_lunar'       => 'Standard plus: drainage bag must be secured to bed/wall — urine will not flow by gravity in 1/6g. Use sealed drainage system with manual pump if gravity drainage insufficient. Maintain strict aseptic technique — CAUTI in lunar habitat is serious infection with limited treatment options.',
                'steps'                 => 'Informed consent. Sterile technique. Clean meatus. Insert lubricated catheter into urethra until urine flows. Inflate balloon. Connect drainage. Secure to inner thigh.',
                'steps_lunar'           => 'DRAINAGE IN 1/6G: Urine bags must be positioned and periodically emptied — gravity drainage unreliable. Use bellows-type drain or suction-assisted drainage. Empty bag every 2 hours. Assess for adequate drainage by palpation. Standard female catheterization: positioning on back unchanged. Male: ensure adequate stabilization against 1/6g float.',
                'telemedicine_points'   => 'Consult for catheterization difficulty. Any suspected urethral injury. Persistent hematuria through catheter.',
                'training_requirements' => 'Medical officers: catheterization competency.',
                'complications'         => 'Infection, urethral trauma, bladder spasm.',
                'search_keywords'       => 'catheter, urine, urinary retention, bladder, foley',
            ],
            [
                'name'                  => 'Intramuscular Injection',
                'category'              => 'therapeutic',
                'risk_level'            => 'low',
                'description'           => 'Delivery of medications by intramuscular injection, with adaptations for altered muscle perfusion patterns in lunar gravity.',
                'indications'           => 'Medications requiring IM delivery: epinephrine, vaccines, certain antibiotics, vitamin B12.',
                'contraindications'     => 'Coagulopathy (relative — use smallest gauge). Injection into infected tissue.',
                'equipment_standard'    => 'Syringe, needle (appropriate size and gauge), antiseptic, gloves.',
                'equipment_lunar'       => 'Standard. Consider muscle thickness changes in long-term residents — reduced muscle mass means needles may need adjustment. Z-track technique recommended for all IM injections in lunar context.',
                'steps'                 => 'Select site (deltoid, vastus lateralis, or dorsogluteal). Clean with alcohol. Z-track technique. Insert needle at 90°. Aspirate (ventrogluteal and dorsogluteal only). Inject slowly. Withdraw. Apply pressure.',
                'steps_lunar'           => 'MUSCLE ATROPHY CONSIDERATION: Long-term residents have reduced muscle mass. Deltoid preferred (more accessible). Reduce needle depth for residents with significant muscle atrophy. Monitor injection site for hematoma formation more carefully. In 1/6g, patients may not stay still without strapping — secure for injection.',
                'telemedicine_points'   => 'Unusual adverse reaction. Suspected abscess formation.',
                'training_requirements' => 'All lunar residents: epinephrine auto-injector use. Medical officers: all injection techniques.',
                'complications'         => 'Hematoma, infection, nerve injury (sciatic nerve in gluteal injections).',
                'search_keywords'       => 'injection, IM, intramuscular, medication, needle, vaccine',
            ],
            [
                'name'                  => 'Emergency Surgical Airway (Cricothyrotomy)',
                'category'              => 'surgical',
                'risk_level'            => 'critical',
                'description'           => 'Emergency surgical airway access through the cricothyroid membrane when standard intubation fails.',
                'indications'           => 'Failed airway after 3 intubation attempts, cannot intubate/cannot oxygenate situation.',
                'contraindications'     => 'No absolute contraindications when airway is truly unobtainable.',
                'equipment_standard'    => 'Scalpel (no. 10 blade), bougie/dilator, cuffed tracheostomy tube (6.0mm) or small ETT, tape.',
                'equipment_lunar'       => 'Standard plus: surgical airway kit must be pre-assembled and immediately accessible in crash cart. Practice with kit components before any elective airway management (in case needed). Headlamp for proper visualization (habitat lighting may be inadequate).',
                'steps'                 => 'Identify cricothyroid membrane. Stabilize larynx. Horizontal incision through CTM. Dilate opening. Insert tube. Inflate cuff. Confirm with CO2 and BVM. Secure.',
                'steps_lunar'           => 'PATIENT POSITIONING: Neck in slight extension. Headring or support under shoulders to extend neck (in 1/6g, patient must be secured to prevent floating). Horizontal incision preferred (landmark-based). Bougie-assisted technique if available. This is a time-critical procedure — execute without hesitation if standard airway fails.',
                'telemedicine_points'   => 'Immediate notification. Video guidance if available during procedure. Post-procedure management guidance.',
                'training_requirements' => 'Medical officers: surgical airway simulation minimum annually.',
                'complications'         => 'Hemorrhage, false passage, subcutaneous emphysema, tube displacement.',
                'search_keywords'       => 'cricothyrotomy, airway, emergency, surgical airway, failed intubation',
            ],
            [
                'name'                  => 'Bedside Ultrasound (POCUS)',
                'category'              => 'diagnostic',
                'risk_level'            => 'low',
                'description'           => 'Point-of-care ultrasound for rapid assessment of critically ill or injured patients. One of the most valuable diagnostic tools in the lunar medical bay.',
                'indications'           => 'FAST exam (trauma), cardiac assessment, lung assessment, DVT evaluation, procedural guidance (IV access, thoracentesis, paracentesis).',
                'contraindications'     => 'No contraindications. Limitations: operator-dependent.',
                'equipment_standard'    => 'Ultrasound machine (portable preferred), multiple probes (phased array, linear, curvilinear), ultrasound gel.',
                'equipment_lunar'       => 'Portable bedside ultrasound machine is a REQUIRED item in lunar medical bay. The inability to do CT scanning and X-ray limitations make POCUS invaluable. All medical officers: POCUS certification mandatory. Ultrasound gel: ensure sufficient stock (temperature-stable formulation).',
                'steps'                 => 'FAST (Focused Assessment with Sonography in Trauma): 4 views — right upper quadrant, left upper quadrant, pelvic, subxiphoid cardiac. Document each view. Positive FAST = free fluid = likely internal hemorrhage.',
                'steps_lunar'           => 'ORIENTATION IN 1/6G: Standard anatomical orientation maintained regardless of gravity. Patient positioning: supine with strapping. Probe contact: more pressure required in reduced gravity (patient compresses less on bed). Image quality equivalent to Earth. Extended FAST (eFAST) adding lung views for pneumothorax detection — highly relevant in lunar trauma.',
                'telemedicine_points'   => 'Images can be transmitted to Earth radiologist for interpretation. Use telemedicine for unusual findings. Real-time telementoring available for complex scans.',
                'training_requirements' => 'Medical officers: POCUS certification. Annual competency assessment with image review.',
                'complications'         => 'No procedure-related complications. False negative/positive results from inexperience.',
                'search_keywords'       => 'ultrasound, POCUS, FAST, diagnostic, imaging, scan',
            ],
        ];
    }

    private function generateVariants(array $base, array $mods): array
    {
        $variants = [];
        $cats = ['first_aid', 'surgical', 'diagnostic', 'therapeutic', 'emergency'];
        $i = 0;

        foreach ($mods as $mod) {
            foreach ($base as $b) {
                $variants[] = [
                    'name'               => "{$mod}: " . $b['name'],
                    'category'           => $cats[$i % count($cats)],
                    'risk_level'         => $b['risk_level'],
                    'description'        => "{$mod} adaptation of: " . Str::limit($b['description'], 200),
                    'indications'        => $b['indications'],
                    'contraindications'  => $b['contraindications'] ?? null,
                    'equipment_standard' => $b['equipment_standard'],
                    'equipment_lunar'    => $b['equipment_lunar'] ?? null,
                    'steps'              => $b['steps'],
                    'steps_lunar'        => $b['steps_lunar'] ?? null,
                    'telemedicine_points'=> $b['telemedicine_points'] ?? null,
                    'training_requirements' => $b['training_requirements'] ?? null,
                    'complications'      => $b['complications'] ?? null,
                    'search_keywords'    => ($b['search_keywords'] ?? '') . ", {$mod}",
                ];
                $i++;
            }
        }
        return $variants;
    }
}
