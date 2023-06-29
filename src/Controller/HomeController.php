<?php

namespace App\Controller;

use App\Entity\Band;
use App\Form\ImportType;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/import', name: 'import')]
    public function importExcel(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ImportType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $file = $form->get('file')->getData();
            $spreadsheet = IOFactory::load($file);
            $worksheet = $spreadsheet->getActiveSheet();

            foreach ($worksheet->getRowIterator() as $row){
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
                    ->setPresentation($rowData[7]);

                $entityManager->persist($band);
            }

            $entityManager->flush();
        }
        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
