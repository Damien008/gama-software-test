import { Component, OnInit } from '@angular/core';
import { BandService} from "../band.service";

@Component({
  selector: 'app-band-list',
  templateUrl: './band-list.component.html',
  styleUrls: ['./band-list.component.scss']
})
export class BandListComponent implements OnInit{
  bands: any[] = [];
  file: File | null = null;
  message: string = '';
  showMessage: boolean = false;
  constructor(private bandService: BandService) { }

  ngOnInit(): void {
    this.getBands();
  }

  getBands(): void{
    this.bandService.getBands().subscribe(
      (response: any) => {
        this.bands = response;
      },
      (error: any) => {
        console.error('Une erreur s\'est produite lors de la récupération des fichiers.', error);
      }
    );
  }

  deleteFile(id: number): void {
    this.bandService.deleteFile(id)
      .subscribe(
        (response) => {
          // Le fichier a été supprimé avec succès
          this.showMessageWithDelay(response.message, 5000);
          this.getBands();
        },
        (error) => {
          // Une erreur s'est produite lors de la suppression du fichier
          console.error('Une erreur s\'est produite lors de la suppression du fichier :', error);
        }
      );
  }

  onFileChange(event: any) {
    this.file = event.target.files[0];
  }

  onSubmit() {
    if (this.file) {
      this.bandService.uploadFile(this.file).subscribe(
        (response) => {
          this.showMessageWithDelay(response.message, 5000);
          this.getBands();
        },
      );
    }
  }

  showMessageWithDelay(message: string, delay: number): void {
    this.message = message;
    this.showMessage = true;

    setTimeout(() => {
      this.showMessage = false;
    }, delay);
  }
}
