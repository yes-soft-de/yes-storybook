import { Component, OnInit } from '@angular/core';
import { FormControl } from '@angular/forms';
import { AuthServiceService } from '../../../service/auth-service.service';

@Component({
  selector: 'app-auth-form',
  templateUrl: './auth-form.component.html',
  styleUrls: ['./auth-form.component.scss']
})
export class AuthFormComponent implements OnInit {
  username = new FormControl('');
  password = new FormControl('');

  constructor(private authService: AuthServiceService) { }

  ngOnInit(): void {
  }

  submit(): void {
    this.authService.login(
      this.username.value,
      this.password.value
    ).subscribe(() => {
    });
  }
}
