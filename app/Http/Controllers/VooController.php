<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;


class VooController extends Controller
{

    private $data = [];
    private $total;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index()
    {

        $response = Http::get('http://prova.123milhas.net/api/flights');
        $response->json();


        $this->data['listaIdGruposTotal'] = [];
        $this->data['listaIdaTarifa'] = [];
        $this->data['listaVoltaTarifa'] = [];

        $this->chaveGrupoTotal = [];
        $totalGrupos = 0;


        $this->id = 0;
        
        $arrayGrupos['flights'] = $response->json();
        $arrayGrupos['Grups'] = [];
       

        foreach ($this->agrupaTarifasEvoos($response->json())['listaTarifas'] as $key => $tarifas) {


            foreach ($this->data['listaVoosIda'][$tarifas] as $key => $idas) {
                $this->total = 0;

                foreach ($this->data['listaVoosVolta'][$tarifas] as $key => $volta) {

                    $this->id++;
                    $this->total = $volta['price'] + $idas['price'];
                    $this->data['listaIdGruposTotal'][$tarifas][$this->id] = $this->total;
                    $this->data['listaIdGruposTotal'][$tarifas] = array_unique($this->data['listaIdGruposTotal'][$tarifas]);


                    // array_push($arrayTotal, [
                    //     'Total' => $this->total,
                    //     'ValorIda' => $idas['price'],
                    //     'ValorVolta' => $volta['price'],
                    //     'IdIda' => $idas['id'],
                    //     'IdVolta' => $volta['id']
                    // ]);


                    $grupos = array_search($this->total, $this->data['listaIdGruposTotal'][$tarifas]);

                    if (!array_key_exists($grupos, $arrayGrupos['Grups'])) {

                        $arrayGrupos['Grups'][$grupos] = [
                            'uniqueId' => $grupos,
                            'totalPrice' => $this->total,
                            'outbound' => [
                                'id' => $idas['id'],
                            ],
                            'inbound' => [
                                "id" => $volta['id'],
                            ],
                            'fare' => $tarifas
                        ];
                    } else {

                        $arrayGrupos['Grups'][$grupos]['outbound'][] = [
                            'Id' => $idas['id']
                        ];
                        $arrayGrupos['Grups'][$grupos]['inbound'][] = [
                            'id' => $volta['id']
                        ];
                    }
                }
            }


            $totalGrupos +=  count($this->data['listaIdGruposTotal'][$tarifas]);
            $menorValor[] =  min($this->data['listaIdGruposTotal'][$tarifas]);
            $idMenorValor[array_search(min($this->data['listaIdGruposTotal'][$tarifas]), $this->data['listaIdGruposTotal'][$tarifas])] = min($this->data['listaIdGruposTotal'][$tarifas]); 
            
        }



            $arrayGrupos['totalGroups'] = $totalGrupos;
            $arrayGrupos['totalFlights'] = count($response->json());
            $arrayGrupos['cheapestPrice'] = min($menorValor);
            $arrayGrupos['cheapestGroup'] =array_search(min($menorValor),$idMenorValor);
        
        
            return response()->json($arrayGrupos, 200);

    }


    //Vifica as taridas da Api
    public function agrupaTarifasEvoos($dadosJson)
    {
        $this->data['listaTarifas'] = [];
        $this->data['listaVoosIda'] = [];
        $this->data['listaVoosVolta'] = [];

        foreach ($dadosJson as $key => $voos) {

            array_push($this->data['listaTarifas'], $voos['fare']);
            $this->data['listaTarifas'] = array_unique($this->data['listaTarifas']);

            if ($voos['outbound']) {
                $this->data['listaVoosIda'][$voos['fare']][] = $voos;
            } else {
                $this->data['listaVoosVolta'][$voos['fare']][] = $voos;
            }
        }

        return $this->data;
    }

  

}
