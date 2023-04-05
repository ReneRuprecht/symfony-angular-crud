import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root',
})
export class HomeService {
  private url = 'http://127.0.0.1:8000';
  constructor(private http: HttpClient) {}

  getHomeResponseFromBackend() {
    return this.http.get(this.url);
  }
}
