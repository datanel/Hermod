<?php

namespace Tests\AppBundle\Controller\v1;

class EquipmentControllerTest extends ApiTestCase
{
    private $missingHeadersCsv = <<<CSV
Code metier;ID Gare;Situation;Direction
CSV;

    private $csv4Equipments = <<<CSV
ID;Code metier;ID Gare;Gare;Gare Active;Situation;Direction;maj
435;"NAP PBS3 ILOT 13";8775802;"GARE DE NANTERRE PREFECTURE";"Oui";"Voirie - Quai";"saint germain en laye";"2013-05-27"
437;"NAP PBS6 (Nord)";8775802;"GARE DE NANTERRE PREFECTURE";"Oui";"Salle des Echanges - Quai 1/1C - Quai 2";"St-Germain-en-Laye et Boissy - Chessy";
436;"NAP PBS5 (Sud)";8775802;"GARE DE NANTERRE PREFECTURE";"Oui";"Salle des Echanges - Quai 2c";"Cergy-Poissy";
448;"E0036/08/1";8711371;"GARE DE VAL DE FONTENAY";"Oui";"rue du Bois Galon - passage souterrain";"CHESSY";
CSV;

    public function testIsSecured()
    {
        $this->assertSecured('POST', 'equipments/csv_update');
    }

    public function testMandatoryCsvHeadersAreEnforced()
    {
        $response = $this->client->request('POST', 'equipments/csv_update', ['body' => $this->missingHeadersCsv]);
        $this->assertError($response, 400, 'bad_request', 'missing headers: \'ID\', \'Gare\'');
    }

    public function testCreateEquipmentsWithCsv()
    {
        $response = $this->client->request('POST', 'equipments/csv_update', ['body' => $this->csv4Equipments]);
        $this->assertEquals(200, $response->getStatusCode());
        $created = $this->getBody($response)['created'];
        $this->assertEquals(4, count($created));
        $this->assertDbCount(4, 'Equipment');

        // equipments already imported, this should not create any equipment
        $response = $this->client->request('POST', 'equipments/csv_update', ['body' => $this->csv4Equipments]);
        $created = $this->getBody($response)['created'];
        $this->assertEquals(0, count($created));
        $this->assertDbCount(4, 'Equipment');
    }

    public function testUpdateEquipmentsWithCsv()
    {
        $this->client->request('POST', 'equipments/csv_update', ['body' => $this->csv4Equipments]);
        $this->assertDbCount(4, 'Equipment');

        $csv3ModifiedEquipments = str_replace('GARE DE NANTERRE', 'GARE DE PARIS', $this->csv4Equipments);
        $response = $this->client->request('POST', 'equipments/csv_update', ['body' => $csv3ModifiedEquipments]);

        $updated = $this->getBody($response)['updated'];
        $this->assertStatus($response, 200);
        $this->assertEquals(3, count($updated));
        $this->assertDbCount(4, 'Equipment');

        // re-playing the same update request, it shouldn't do anything as the data are already up to date
        $response = $this->client->request('POST', 'equipments/csv_update', ['body' => $csv3ModifiedEquipments]);
        $updated = $this->getBody($response)['updated'];
        $this->assertStatus($response, 200);
        $this->assertEquals(0, count($updated));
        $this->assertDbCount(4, 'Equipment');
    }
}
