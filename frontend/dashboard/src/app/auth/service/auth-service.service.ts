import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { AuthRepositoryService } from '../repository/auth-repository.service';
import { map, tap } from 'rxjs/operators';
import { AuthResponse } from '../response/auth-response';

@Injectable({
  providedIn: 'root'
})
export class AuthServiceService {

  constructor(private authRepository: AuthRepositoryService) { }

  login(username: string, password: string): Observable<boolean> {
    return this.authRepository.login({
      username,
      password
    }).pipe(
      tap((val: AuthResponse) => {
        sessionStorage.setItem('token', `Bearer ${val.token}`);
      }),
      map((val: any) => val !== null),
    );
  }
}
