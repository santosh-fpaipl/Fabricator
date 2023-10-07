<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FabricatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //For creating Supplier

        $users = [
            [
                'name' => 'Fabricator 11',
                'email' => 'fabricator11@example.com',
            ],
            [
                'name' => 'Fabricator 22',
                'email' => 'fabricator22@example.com',
            ],
            [
                'name' => 'Fabricator 33',
                'email' => 'fabricator33@example.com',
            ],

        ];

        foreach($users as $user){

            $newUser = \App\Models\User::factory()->create($user);
            $newSupplier = \App\Models\Fabricator::create([
                'user_id' => $newUser->id,
                'sid' =>'F'.$newUser->id,
                'active' => 1,
            ]);

            $newAddress = \App\Models\Address::create([
                'fabricator_id' => $newSupplier->id,
                'fname' => 'Fabricator'.$newSupplier->id,
                'lname' => 'Singh',
                'contacts' => '8527117535',
                'line1' => 'Okhla',
                'line2' => 'phase 2',
                'district' => 'SOUTH WEST DELHI',
                'state' => 'Delhi',
                'country' => 'india',
                'pincode' => '435435',
                'district_id' => 120,
                'state_id' => 6,
            ]);

            $newAddress->print = $this->calculatePrint($newAddress);
            $newAddress->save();
            
        }
    }

    public function calculatePrint($address){
        $seperator = " ,";
        $print = $address->fname.$seperator;
        $print .= $address->lname.$seperator;
        $print .= $address->contacts.$seperator;
        $print .= $address->line1.$seperator;
        $print .= $address->line2.$seperator;
        $print .= $address->district.$seperator;
        $print .= $address->state.$seperator;
        $print .= $address->country.$seperator;
        $print .= $address->pincode;
        return $print;
    }
}
