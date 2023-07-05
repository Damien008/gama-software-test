import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { BandService } from '../band.service';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { HttpErrorResponse } from '@angular/common/http';

@Component({
  selector: 'app-edit-form',
  templateUrl: './edit-form.component.html',
  styleUrls: ['./edit-form.component.scss']
})
export class EditFormComponent implements OnInit {
  entityId!: number;
  editForm!: FormGroup;
  errorMessage: string = '';

  constructor(
    private route: ActivatedRoute,
    private router: Router,
    private bandService: BandService,
    private formBuilder: FormBuilder
  ) { }

  ngOnInit(): void {
    this.entityId = +this.route.snapshot.paramMap.get('id')!;
    this.initializeForm();
    this.getBand();
  }

  getBand(): void {
    this.bandService.getBand(this.entityId).subscribe(
      (response: any) => {
        // Mettre à jour les valeurs du formulaire avec les données
        this.editForm.patchValue({
          name: response.name,
          country: response.country,
          city: response.city,
          startYear: response.startYear,
          endYear: response.endYear,
          founder: response.founder,
          musicalType: response.musicalType,
          numberOfMembers: response.numberOfMembers,
          presentation: response.presentation,
        });
      },
      (error: HttpErrorResponse) => {
        console.error('Une erreur s\'est produite lors de la récupération des informations de la bande.', error);
      }
    );
  }

  initializeForm(): void {
    this.editForm = this.formBuilder.group({
      name: ['', Validators.required],
      country: ['', Validators.required],
      city: ['', Validators.required],
      startYear: ['', Validators.required],
      endYear: [''],
      founder: [''],
      musicalType: [''],
      numberOfMembers: ['', Validators.required],
      presentation: ['', Validators.required],
    });
  }

  onSubmit(): void {
    if (this.editForm.invalid) {
      return;
    }

    const formData = this.editForm.value;

    this.bandService.updateBand(this.entityId, formData).subscribe(
      () => {
        this.router.navigate(['/']);
      },
      (error: HttpErrorResponse) => {
        if (error.error instanceof ErrorEvent) {
          // Erreur côté client
          this.errorMessage = 'Une erreur s\'est produite lors de la mise à jour de la bande : ' + error.error.message;
        } else {
          // Erreur côté serveur
          this.errorMessage = 'Une erreur s\'est produite lors de la mise à jour de la bande. Veuillez réessayer ultérieurement.';
        }
      }
    );
  }
}
