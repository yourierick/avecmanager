<?php

namespace App\Console\Commands;

use App\Models\Avec;
use App\Models\CaisseAmande;
use Illuminate\Console\Command;

class CalculateMemberGain extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate:member-gain';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'calculer les gains pour chaque membre au prorata de ses parts achetées';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $avecs = Avec::with(['membres'])->get();

        foreach ($avecs as $avec) {
            $caisseamande = CaisseAmande::where("avec_id", $avec->id)->first();
            $totalApartager = $caisseamande ? $caisseamande->montant : 0 + $avec->interets;
            $totalPartsAchetees = $avec->membres->sum('part_tot_achetees');

            if ($totalPartsAchetees == 0) {
                $this->info("Aucun membre n'a de part achetée dans l'AVEC {$avec->designation}. Aucun gain calculé");
                continue;
            }

            foreach ($avec->membres as $membre) {
                if ($membre->statut != "abandon"){
                    $gain = round(($membre->part_tot_achetees/$totalPartsAchetees) * $totalApartager, 1);
                    $membre->gains = $gain;
                    $membre->save();

                    $this->info("gain calculé pour {$membre->nom} dans l'AVEC {$avec->designation}: {$gain}");
                }
            }
        }
        $this->info("Tous les gains ont été calculés avec succès.");
    }
}
