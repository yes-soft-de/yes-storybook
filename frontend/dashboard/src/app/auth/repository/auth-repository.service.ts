import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { AuthResponse } from '../response/auth-response';
import { AuthRequest } from '../resquest/auth-request';

@Injectable({
  providedIn: 'root'
})
export class AuthRepositoryService {

  constructor(private httpClient: HttpClient) { }

  login(authRequest: AuthRequest): Observable<AuthResponse> {
    return this.httpClient.post<AuthResponse>(
      'http://c4d.yes-cloud.de/html/public/index.php/login_check',
      authRequest
    );
  }
}
