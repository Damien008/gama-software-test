import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class BandService {
  private baseUrl = 'http://localhost:8000/api/band'; // Remplacez par l'URL de votre API Symfony

  constructor(private http: HttpClient) { }

  getBands(): Observable<any> {
    return this.http.get(`${this.baseUrl}/data`);
  }

  uploadFile(file: File): Observable<any> {
    const formData: FormData = new FormData();
    formData.append('file', file, file.name);
    return this.http.post(`${this.baseUrl}/import`, formData);
  }

  getBand(id: number){
    return this.http.get(`${this.baseUrl}/${id}/get_one`);
  }
  updateBand(id: number, entity: any){
    return this.http.put(`${this.baseUrl}/${id}/edit`, entity);
  }
  deleteFile(id: number): Observable<any> {
    return this.http.delete(`${this.baseUrl}/${id}/delete`);
  }
}
