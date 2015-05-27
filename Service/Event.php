<?php

class Event{

    public function getAllEvents($actifs = null, $name = null)
    {
        $colloque = Colloque::select('id_Colloque','organisateur','titre','soustitre','endroit','dateDebut','dateFin','typeColloque','DelaiInscription','remarques','visible','centre_logos')->where('visible','=','1');

        if($name)
        {
            $colloque->where('titre','LIKE','%'.$name.'%');
        }

        if($actifs)
        {
            $colloque->where('dateDebut','>=',date("Y-m-d"))->orderBy('dateDebut', 'desc');
        }
        else{
            $colloque->where('dateFin','<',date("Y-m-d"))->orderBy('dateFin', 'desc');
        }

        return  $colloque->get();
    }

    public function getEvent($id)
    {
        $colloque = Colloque::select('id_Colloque','organisateur','titre','soustitre','endroit','dateDebut','dateFin','typeColloque','DelaiInscription','remarques','visible','centre_logos')
            ->where('visible','=','1')->where('id_Colloque','=',$id)->get();

        if(!$colloque->isEmpty())
        {
            return $colloque->first();
        }
    }

    public function dispatchEvents($events, $centres){

        if(!empty($events))
        {
            foreach($events as $event)
            {
                if( !empty($centres) )
                {
                    $isOrganisateur = $this->isOrganisateur($event,$centres);

                    if(!empty($isOrganisateur))
                    {
                        $actifs[] = $this->prepareEvent($event,$isOrganisateur);
                    }
                }
                else
                {
                    $actifs[] = $this->prepareEvent($event);
                }
            }

            return (!empty($actifs) ? $actifs : array());
        }

        return false;
    }

    public function prepareEvent($event, $isOrganisateur = null){

        $prix       = Price::where('refColloque','=',$event['id_Colloque'])->where('refTypePrix','=','1')->get();
        $programme  = File::where('refColloque','=',$event['id_Colloque'])->where('typeFile','=','pdfpublic')->get();

        if(!$programme->isEmpty())
        {
            $programme  = $programme->first()->toArray();
            $programme['url'] = 'http://www.publications-droit.ch/fileadmin/admin_unine/files/';
        }

        $data['event']        = $event;
        $data['prix']         = $prix->toArray();
        $data['programme']    = (!empty($programme) ? $programme : '');
        $data['organisateur'] = ($isOrganisateur ? $isOrganisateur : '');

        return $data;
    }

    /**
     *  Colloques du cert ou cemaj test par centre
     */
    public function isOrganisateur($event,$centres){

        $organisateurs = (!empty($event['centre_logos']) ? $event['centre_logos'] : array());

        $centres = array_map('strtolower', $centres);

        if(!empty($organisateurs))
        {
            $organisateurs = explode(',', $organisateurs);
            $existCentre   = array_intersect($centres, $organisateurs);

            if(!empty($existCentre))
            {
                // re-index tableau
                return array_values($existCentre);
            }
            return false;
        }

        return false;

    }

}