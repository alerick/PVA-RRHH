<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   *
   * @return void
   */
  public function run()
  {
    $this->command->info('Unguarding models');
    Model::unguard();
    
    $tables = [
      'company_accounts',
      'companies',
      'users',
      'cities',
      'company_addresses',
      'insurance_companies',
      'management_entities',
      'employer_numbers',
      'position_groups',
      'dependency_position_group',
      'charges',
      'positions',
      'retirement_reasons',
      'contract_types',
      'employees',
    ];

    $this->command->info('Truncating existing tables');
    foreach ($tables as $table) {
      DB::statement('TRUNCATE TABLE ' . $table . ' CASCADE;');
    }

    $this->call(UserSeeder::class);
    $this->call(CitySeeder::class);
    $this->call(InsuranceCompanySeeder::class);
    $this->call(ManagementEntitySeeder::class);
    $this->call(CompanySeeder::class);
    $this->call(PositionGroupSeeder::class);
    $this->call(CompanyAddressSeeder::class);
    $this->call(PositionGroupAddressSeeder::class);
    $this->call(ChargeSeeder::class);
    $this->call(PositionSeeder::class);
    $this->call(RetirementReasonSeeder::class);
    $this->call(ContractTypeSeeder::class);
    $this->call(EmployeeSeeder::class);
  }
}
