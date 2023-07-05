<?php

namespace App\Controller;

use ApiPlatform\Validator\ValidatorInterface;
use App\Entity\Band;
use App\Form\BandType;
use App\Form\ImportType;
use App\Repository\BandRepository;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/band', name: 'api_band_')]
class BandController extends AbstractController
{
    public function __construct(private BandRepository $bandRepository, private ValidatorInterface $validator){

    }

    /**
     * @return Response
     */
    #[Route('/data', name: 'data')]
    public function getData(): Response
    {
        $data = $this->bandRepository->findAll();

        return $this->json($data);
    }

    /**
     * @param int $id
     * @return Response
     */
    #[Route('/{id}/get_one', name: 'get_one')]
    public function getBand(int $id): Response
    {
        $band = $this->bandRepository->find($id);

        if (!$band) {
            return $this->json(['error' => 'Entity not found.'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($band);
    }

    /**
     * @param Request $request
     * @param Band $band
     * @return JsonResponse
     */
    #[Route('/{id}/edit', name: 'edit', methods: ['PUT'])]
    public function edit(Request $request, Band $band): Response
    {
        // Récupérer les données soumises dans la requête
        $data = json_decode($request->getContent(), true);

        // Mettre à jour les propriétés de l'entité Band avec les nouvelles valeurs
        $band->setName($data['name'])
            ->setCountry($data['country'])
            ->setCity($data['city'])
            ->setStartYear($data['startYear'])
            ->setEndYear($data['endYear'])
            ->setNumberOfMembers($data['numberOfMembers'])
            ->setMusicalType($data['musicalType'])
            ->setFounder($data['founder'])
            ->setPresentation($data['presentation'])
        ;

        // Valider l'entité avec le validateur d'API Platform
        $this->validator->validate($band);

        // Enregistrer les modifications dans la base de données
        $this->bandRepository->save($band, true);

        // Répondre avec un message de succès
        return $this->json(['message' => 'Mise à jour réussie']);
    }

    /**
     * @param int $id
     * @return Response
     */
    #[Route('/{id}/delete', name: 'delete')]
    public function delete(int $id): Response
    {
        $band = $this->bandRepository->find($id);
        $this->bandRepository->remove($band, true);
        return $this->json(['message' => 'Le fichier a bien été supprimé']);
    }

    /**
     * @param Request $request
     * @param BandRepository $bandRepository
     * @return Response
     * @throws Exception
     */
    #[Route('/import', name: 'import')]
    public function importExcel(Request $request, BandRepository $bandRepository): Response
    {

        $file = $request->files->get('file');

        if ($file) {
            $spreadsheet = IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $worksheet->removeRow(1);
            // Parcourez les lignes et traitez les données
            foreach ($worksheet->getRowIterator() as $row) {
                // Traitement des données de chaque ligne
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);

                $rowData = [];
                foreach ($cellIterator as $cell){
                    $rowData[] = $cell->getValue();
                }

                $band = new Band();
                $band->setName($rowData[0])
                    ->setCountry($rowData[1])
                    ->setCity($rowData[2])
                    ->setStartYear(intval($rowData[3]))
                    ->setEndYear(intval($rowData[4]))
                    ->setFounder($rowData[5])
                    ->setNumberOfMembers(intval($rowData[6]))
                    ->setMusicalType($rowData[7])
                    ->setPresentation($rowData[8]);

                $bandRepository->save($band, true);
            }

            // Répondez avec un message de succès ou autre réponse appropriée
            return $this->json(['message' => 'Import réussi']);
        }

        // Répondez avec une réponse d'erreur si aucun fichier n'a été trouvé
        return $this->json(['message' => 'Import échoué'], 400);
    }
}
