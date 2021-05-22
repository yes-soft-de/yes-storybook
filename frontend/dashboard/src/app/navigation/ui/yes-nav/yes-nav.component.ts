import { Component, Input, OnInit, Output, EventEmitter } from '@angular/core';
import { UserModel } from '../../../auth/model/user-model';

@Component({
  selector: 'yes-nav',
  templateUrl: './yes-nav.component.html',
  styleUrls: ['./yes-nav.component.scss']
})
export class YesNavComponent implements OnInit {
  @Input() user: UserModel | undefined;
  @Output() onLogout = new EventEmitter<any>();
  @Output() onLogin = new EventEmitter<any>();

  constructor() { }

  ngOnInit(): void {
  }

}
