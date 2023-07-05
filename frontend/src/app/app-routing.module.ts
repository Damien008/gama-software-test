import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import {BandListComponent} from "./band-list/band-list.component";
import {EditFormComponent} from "./edit-form/edit-form.component";

const routes: Routes = [
  { path: '', component: BandListComponent },
  { path: ':id/edit', component: EditFormComponent }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
